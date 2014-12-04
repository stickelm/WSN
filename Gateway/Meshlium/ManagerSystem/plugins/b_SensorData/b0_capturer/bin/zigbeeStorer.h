/*
 *  Copyright (C) Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version:            0.1
 *  Design:             David Gascon
 *  Implementation:     Manuel Calvo, Octavio Benedi
 *  Improved by:	Joaquin Ruiz
 */

#include <stdio.h> 
#include <string.h> 
#include <unistd.h> 
#include <fcntl.h> 
#include <errno.h> 
#include <stdlib.h> 
#include <termios.h> /* Terminal control library (POSIX) */ 
#include <ctype.h> 
#include <mysql.h>
#include <time.h>

 
 
#define MAX 500 
#define MAX_SQL 700
#define LOCAL 0
#define EXTERNAL 1


int sd = 3; 
char *serialPort = "";
char *serialPort0 = "/dev/ttyS0";
char valor[MAX] = "";
char c;
struct termios opciones;

char sql[MAX_SQL] = "";
MYSQL mysql;



struct xbee_frame {
   char mac[20];
   char x[20];
   char y[20];
   char z[20];
   char temp[20];
   char bat[20];
   char frame[200];
};
struct xbee_frame strct_frame;

char aux[MAX] = "";

int init(int argc, char *argv[]);
void getFrame();
void parseFrame(struct xbee_frame *p_frame);
int storeInAFile(char * fileName, struct xbee_frame *p_frame);
int showMeNowFile(struct xbee_frame *p_frame);
int storeInADB(struct xbee_frame *p_frame, int to);

