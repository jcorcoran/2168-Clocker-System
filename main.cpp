#include <errno.h>      // Error number definitions
#include <stdint.h>     // C99 fixed data types
#include <stdio.h>      // Standard input/output definitions
#include <stdlib.h>     // C standard library
#include <string.h>     // String function definitions
#include <unistd.h>     // UNIX standard function definitions
#include <fcntl.h>      // File control definitions
#include <termios.h>    // POSIX terminal control definitions
#include <iostream>		// Used for writing to console
#include <fstream>		// Used for writing to a file
#include <pthread.h>	// Separate threads library
#include <unistd.h>
#include <set>
#include <sstream>
#include <map>

using namespace std;

double clock_time = 0;
void *Clock(void *obj){
	while(true){
		sleep(1);
		clock_time++;
	}

	return NULL;
}
ofstream fout;
int main(int argc, char* argv[]){

	set<string> ClockerData;
	set<string>::iterator ClockerDataIT;

	//Start Clocker Thread
	pthread_t system_clock;
	pthread_create(&system_clock, NULL, &Clock, (void*) "ThreadName");

	int fd_serialport;
	struct termios options;

	//open up serial port
	fd_serialport = open("/dev/ttyUSB0", O_RDWR | O_NOCTTY | O_NDELAY);

	if(fd_serialport == -1){
		perror("Unable to open /dev/ttyUSB0");
	}

	//Set up beaglebone and the serial port for reading
	tcgetattr(fd_serialport, &options);
	cfsetispeed(&options, B9600);
	cfsetospeed(&options, B9600);
	options.c_cflag |= (CLOCAL | CREAD);
	options.c_cflag &= ~PARENB;    // set no parity, stop bits, data bits
	options.c_cflag &= ~CSTOPB;
	options.c_cflag &= ~CSIZE;
	options.c_cflag |= CS8;
	options.c_iflag |= (INPCK | ISTRIP);
	tcsetattr(fd_serialport, TCSANOW, &options);

	fcntl(fd_serialport, F_SETFL, 0);
	char buffer[13];


	bool skipwrite = false;
	bool CardFound = false;
	map<string,double> TimeDB;
	//std::pair<std::map<string,int>::iterator,bool> ret;
	double timespent;


	while(true){
	//read in and display the value
	int n = read(fd_serialport, buffer, sizeof(buffer));
		if (n < 0){
			cout << "read failed!\n";
			return 1;
		}

		//check if the buffer is an error buffer
		if(buffer[0] == 10){
			skipwrite = true;
		}

		//If its a valid enter do stuff with the card key
		if(!skipwrite){
			//cout << "Found Card" << endl;
			//cout << buffer << endl;
			string CardKey(buffer);
			//Check if this is the users clock in or clock out swipe
			for (ClockerDataIT=ClockerData.begin(); ClockerDataIT!=ClockerData.end(); ClockerDataIT++){
			    if(CardKey == *ClockerDataIT){
			    	ClockerData.erase(ClockerDataIT);
			    	CardFound = true;

			    }
			}


			if(!CardFound){
				cout << "clock in" << endl << endl;
				ClockerData.insert(CardKey);
				TimeDB.insert(pair<string,int>(CardKey,clock_time));
				memset(&buffer[0], 0, sizeof(buffer));
			}
			if(CardFound){

				//Calculate Time Spent
				map<string,double>::iterator TimeDbIT = TimeDB.begin();
				for(TimeDbIT = TimeDB.begin(); TimeDbIT != TimeDB.end(); TimeDbIT++){
					if((string)TimeDbIT->first == CardKey){
						timespent = clock_time - TimeDbIT->second;
						TimeDB.erase(TimeDbIT);
					}
				}

				//Write Data To A File
				fout.open("/ClockerSystem/LOG",std::ios_base::app);
				int bufferindex = 1;
				for(bufferindex = 1; bufferindex <=12; bufferindex++){
					fout<<buffer[bufferindex];
				}
				fout << ',' << timespent << endl;
				fout.close();
				cout << "Wrote Data! Clocked Out And Spent " << timespent << endl << endl;
			}

	}
		CardFound = false;
		skipwrite = false;
		timespent = 0;
}
	return 0;
}
