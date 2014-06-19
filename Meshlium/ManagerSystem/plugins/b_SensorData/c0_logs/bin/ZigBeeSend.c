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
 *  Version:         0.1
 *  Design:          David Gascon
 *  Implementation:  Manuel Calvo, Octavio Benedi
 */

/*
 * Send to MAC ej. ./ZigBeeSend -mac 0013a2004069165d "Hello world"
 * Send to NET ej. ./ZigBeeSend -net 1234 "hello world"
 * Send to ALL ej. ./ZigBeeSend -b "hello world"
 */

#include <stdio.h>
#include <string.h> 
#include <unistd.h>
#include <fcntl.h>
#include <errno.h>
#include <stdlib.h>
#include <termios.h>
#include <netinet/in.h>
#include <pthread.h>
#include <signal.h>
#include <sys/ipc.h>
#include <sys/sem.h>
#include <sys/socket.h>
#include <sys/time.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <time.h>
#include <ctype.h>

#define MAX 100

int end=0;
char c;
char atmymy[80] = "";
int readserial = 0;
int count = 0;
int longitude = 0;

int sd = 3;
char *serialPort = "/dev/ttyS0";
char str[MAX] = "";

struct termios opciones;

int speed = B38400;

/**************************************************
* CODE FOR THREAD 2
**************************************************/
void * read_serial( void * temp_pt )
{
   while(!end)
   {
      if (read(sd,&c,1)!=0)
      if ((isprint(c)!=0) || (c=='\n'))
      //fprintf(stderr, &c, 1);
      if(readserial)
      {
          atmymy[count] = c;
          count++;
      }
   }
    return 0;
}

int init(int argc, char *argv[])
{
   if ((sd = open(serialPort, O_RDWR | O_NOCTTY | O_NONBLOCK)) == -1)
   {
      fprintf(stderr, "Unable to open the serial port %s - \n", serialPort);
      exit(-1);
   }
   else
   {
      if (!sd)
      {
         /* Sometimes the first time you call open it does not return the
          * right value (3) of the free file descriptor to use, for this
          * reason you can set manually the sd value to 3 or call again
          * the open function (normally returning 4 to sd), advised!
          */
         sd = open(serialPort, O_RDWR | O_NOCTTY | O_NONBLOCK);
      }

      //fprintf(stderr,"Serial Port open at: %i\n", sd);
      fcntl(sd, F_SETFL, 0);
   }

   tcgetattr(sd, &opciones);
   cfsetispeed(&opciones, speed);
   cfsetospeed(&opciones, speed);
   opciones.c_cflag |= (CLOCAL | CREAD);

   /*
   * No parity
   */
   opciones.c_cflag &= ~PARENB;
   opciones.c_cflag &= ~CSTOPB;
   opciones.c_cflag &= ~CSIZE;
   opciones.c_cflag |= CS8;

   /*
   * raw input:
   * making the applycation ready to receive
   */
   opciones.c_lflag &= ~(ICANON | ECHO | ECHOE | ISIG);

   /*
   *Ignore parity errors
   */
   opciones.c_iflag |= ~(INPCK | ISTRIP | PARMRK);
   opciones.c_iflag |= IGNPAR;
   opciones.c_iflag &= ~(IXON | IXOFF | IXANY | IGNCR | IGNBRK);
   opciones.c_iflag |= BRKINT;

   /*
   * raw output
   * making the applycation ready to transmit
   */
   opciones.c_oflag &= ~OPOST;

   /*
   * apply
   */
   tcsetattr(sd, TCSANOW, &opciones);


   pthread_t thread1;

   // initializing thread that scan serial port for incoming data.
   if( pthread_create( &thread1, NULL, read_serial, NULL ) != 0 )
   {
      printf("Cannot create thread1 , exiting \n ");
      exit(-1); // exit with errors
   }
}

void sendBroadcast(int argc, char *argv[])
{
   char mymy[15] = "";
   char response[64] = "";

   fprintf(stderr, "Preparing the module... \n");
   // at mode, writting +++, xbee returns Ok, 
   // so then is posible write at commands to setup module
   write(sd,"+++",3);
   sleep(2);

   /*
    * An at command is composed by:
    * "at"+"parameter to configure"+"value"+"\r"+"\n" -> to set value
    * "at"+"parameter to configure"+"\r"+"\n" -> to get value
    */ 

   //set destination mac hi -> set 32 most significant bits
   write(sd,"atdh00000000\r\n",14);
   usleep(70000);

   //set destination mac low -> set 32 less significant bits
   write(sd,"atdl0000ffff\r\n",14);
   usleep(70000);

   // save my atmy (my network id)
   // get the current value of my network id
   readserial=1;
      write(sd,"atmy\r\n",6);
      //usleep(70000);
      usleep(170000);
      strcpy (mymy, atmymy);
   readserial=0;

   // set atmy to ffff
   // to send in 64 bit mode, the network id has to be set to ffff
   write(sd,"atmyffff\r\n",10);
   usleep(70000);

   // leave at mode -> close connection
   write(sd,"atcn\r",5);
   usleep(70000);


   fprintf(stderr, "Sending message... \n");
   // send message -> write throught serial port
   // set the waspmote API headers
   int id = 48;
   int numFragmentos = 1;
   int sharp = 35;
   int idType = 2;
   char ni[] = "meshlium#";
   write(sd, &id, 1);
   write(sd, &numFragmentos, 1);
   write(sd, &sharp, 1);
   write(sd, &idType, 1);
   write(sd, ni, 9);
   // send message
   write (sd, argv[2], longitude);


   fprintf(stderr, "Awaiting response: ");
   // read response
   // get message that comes from waspmote
   count = 0;
   readserial=1;
      sleep(5);
      strcpy (response, atmymy);
      if(!strcmp(response,mymy))
      {
         // module doesn't get response
         fprintf(stderr,"ERROR\n");
      }
      else
      {
         fprintf(stderr, response);
         fprintf(stderr, "\n");
      }
   readserial=0;


   fprintf(stderr, "Returning to original values... \n");
   // at mode
   write(sd,"+++",3);
   sleep(2);

   // make atmy command (my network id)
   char atmy[20] = "atmy";
   strcat (atmy, mymy);
   strcat (atmy, "\r\n");

   // restore atmy
   write(sd,atmy,10);
   usleep(70000);

   // leave at mode -> close connection
   write(sd,"atcn\r",5);
   usleep(70000);
}

void sendMac(int argc, char *argv[])
{
   char mymy[15] = "";
   char response[64] = "";
   char macHigh[15] = "";
   char macLow[15] = "";
   char mac;


   fprintf(stderr, "Preparing the module... \n");
   // divide mac that comes from argv[] in:
   // 32 most significant bits and
   // 32 less significant bits
   strncpy(macHigh,argv[2],8);
   strncpy(macLow,argv[2]+8,8);

   // at mode
   write(sd,"+++",3);
   sleep(2);

   /*
    * An at command is composed by:
    * "at"+"parameter to configure"+"value"+"\r"+"\n" -> to set value
    * "at"+"parameter to configure"+"\r"+"\n" -> to get value
    */ 

   // make atdh & atdl command
   // atdh -> set 32 most significant bits
   // atdl -> set 32 less significant bits
   char ath[20] = "atdh";
   strcat (ath, macHigh);
   strcat (ath, "\r\n");
   char atl[20] = "atdl";
   strcat (atl, macLow);
   strcat (atl, "\r\n");

   // set atdh
   write(sd,ath,14);
   usleep(70000);

   // set atdl
   write(sd,atl,14);
   usleep(70000);

   // save my atmy to be restored after send message
   readserial=1;
      write(sd,"atmy\r\n",6);
      //usleep(70000);
      usleep(170000);
      strcpy (mymy, atmymy);
   readserial=0;

   // set atmy as ffff (network id)
   // to send in 64 bit mode, the network id has to be set to ffff
   write(sd,"atmyffff\r\n",10);
   usleep(70000);

   // leave at mode -> close connection
   write(sd,"atcn\r",5);
   usleep(70000);


   fprintf(stderr, "Sending message... \n");
   // send message -> write throught serial port
   // set the waspmote API headers
   int id = 48;
   int numFragmentos = 1;
   int sharp = 35;
   int idType = 2;
   char ni[] = "meshlium#";
   write(sd, &id, 1);
   write(sd, &numFragmentos, 1);
   write(sd, &sharp, 1);
   write(sd, &idType, 1);
   write(sd, ni, 9);
   // send message
   write ( sd, argv[3], longitude);


   fprintf(stderr, "Awaiting response: ");
   // read response
   // get message that comes from waspmote
   count = 0;
   readserial=1;
      sleep(5);
      strcpy (response, atmymy);
      if(!strcmp(response,mymy))
      {
         // module doesn't get response
         fprintf(stderr,"ERROR\n");
      }
      else
      {
         fprintf(stderr, response);
         fprintf(stderr, "\n");
      }
   readserial=0;


   fprintf(stderr, "Returning to original values... \n");
   // at mode
   write(sd,"+++",3);
   sleep(2);

   // make atmy command to be restored
   char atmy[20] = "atmy";
   strcat (atmy, mymy);
   strcat (atmy, "\r\n");

   // restore atmy
   write(sd,atmy,10);
   usleep(70000);

   // leave at mode -> close connection
   write(sd,"atcn\r",5);
   usleep(70000);
}

void sendNet(int argc, char *argv[])
{
   char response[64] = "";

   fprintf(stderr, "Preparing the module... \n");
   // at mode
   write(sd,"+++",3);
   sleep(2);

   /*
    * An at command is composed by:
    * "at"+"parameter to configure"+"value"+"\r"+"\n" -> to set value
    * "at"+"parameter to configure"+"\r"+"\n" -> to get value
    */ 

   // set mac hi -> 32 most significant bits
   write(sd,"atdh00000000\r\n",14);
   usleep(70000);

   // make mac low-> 32 less significant bits
   char atl[20] = "atdl0000";
   strcat (atl, argv[2]);
   strcat (atl, "\r\n");

   // set mac low -> 32 less significant bits
   write(sd,atl,14);
   usleep(70000);

   // leave at mode -> close connection
   write(sd,"atcn\r",5);
   usleep(70000);

   fprintf(stderr, "Sending message... \n");
   // send message -> write throught serial port
   // set the waspmote API headers
   int id = 48;
   int numFragmentos = 1;
   int sharp = 35;
   int idType = 2;
   char ni[] = "meshlium#";
   write(sd, &id, 1);
   write(sd, &numFragmentos, 1);
   write(sd, &sharp, 1);
   write(sd, &idType, 1);
   write(sd, ni, 9);
   // send message
   write ( sd, argv[3], longitude);

   fprintf(stderr, "Awaiting response: ");
   // read response
   // get message that comes from waspmote
   count = 0;
   readserial=1;
      sleep(5);
      strcpy (response, atmymy);
      if(!strcmp(response,""))
      {
         // module doesn't get response
         fprintf(stderr,"ERROR\n");
      }
      else
      {
         fprintf(stderr, response);
         fprintf(stderr, "\n");
      }
   readserial=0;
}

void * printERROR(char *argv[])
{
   fprintf(stderr, "USAGE: %s -mac mac message \n", argv[0]);
   fprintf(stderr, "USAGE: %s -net net message \n", argv[0]);
   fprintf(stderr, "USAGE: %s -b message \n", argv[0]);
   return 0;
}

void * checkARGS(int argc, char *argv[])
{
   if (argc == 1)
   {
      printERROR(argv);
      exit (-1);
   }

   if (argc != 2)
   {
      if (!strcmp(argv[1], "-h"))
      {
         printERROR(argv);
      }
      if (!strcmp(argv[1], "--help"))
      {
         printERROR(argv);
      }
   }

   if ((argc != 3) && (argc != 4))
   {
      printERROR(argv);
      exit (-1);
   }
}

int main(int argc, char *argv[])
{
   checkARGS(argc, argv);
   init(argc, argv);

   /*
    * Send in broadcast
    */
   if (argc == 3)
   {
      // limiting the message
      if (strlen(argv[2]) >= 60)
      {
         longitude = 60;
      }
      else
      {
         longitude = strlen(argv[2]);
      }

      if (!strcmp(argv[1], "-b"))
      {
         sendBroadcast(argc, argv);
      }
   }


   /*
    * Send by net or by mac
    */
   if (argc == 4)
   {
      // limiting the message
      if (strlen(argv[3]) >= 60)
      {
         longitude = 60;
      }
      else
      {
         longitude = strlen(argv[3]);
      }

      /*
       * Mode mac
       */
      if (!strcmp(argv[1], "-mac"))
      {
         sendMac(argc, argv);
      }

      /*
       * Mode net
       */
      if(!strcmp(argv[1], "-net"))
      {
         sendNet(argc, argv);
      }
   }

    close(sd);
    exit(0);
}
