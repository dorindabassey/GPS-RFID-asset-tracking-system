
#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <SPI.h>
#include <MFRC522.h>

#define RST_PIN         0           // Configurable, see typical pin layout above
#define SS_PIN          15          // Configurable, see typical pin layout above

MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance
MFRC522::MIFARE_Key key;

// The TinyGPS++ object
TinyGPSPlus gps;
// The serial connection to the GPS device
// 13, 12 CORESPONDS TO 7,6
//
//SoftwareSerial ss(13, 12); //RX/TX
SoftwareSerial ss(4, 5); //RX/TX
//NETWORK Credentials
const char* ssid     = "SamsungS5 0708";
const char* pass = "dorianagala";
//const char* ssid     = "no nonsense";
//const char* pass = "kleezpass";

// REPLACE with your Domain name and URL path or IP address with path
const char* host = "gpslogistics.000webhostapp.com";
const uint16_t port = 443;
const char * path = "/postgpss.php";

String apiKeyValue = "AIzaSyDFfuTmxYsemTjthPUWKyOwLyAPT2tlogg";
double latit;
double longi;
uint8_t fixCount = 0;
boolean bitt;
boolean updateserver = false;
String pproduct;
boolean startt = false;
boolean startter = true;
void setup() {
  Serial.begin(115200);
  ss.begin(9600);
  SPI.begin();                                                  // Init SPI bus
  mfrc522.PCD_Init();                                              // Init MFRC522 card
  // Prepare key - all keys are set to FFFFFFFFFFFFh at chip delivery from the factory.

  for (byte i = 0; i < 6; i++) key.keyByte[i] = 0xDD;
  Serial.println(F("Read personal data on a MIFARE PICC:"));    //shows in serial that it is ready to read
  Serial.println("setup is ready");
  pinMode(2, OUTPUT);
  WiFi.begin(ssid, pass);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  digitalWrite(15, HIGH);
  delay(1000);
  digitalWrite(15, LOW);
  delay(1000);
}
//*****************************************************************************************//


void loop() {
  //initi = false;
  // if (initi == false) {


  //some variables we need
  byte block;
  byte len;
  MFRC522::StatusCode status;
  // This sketch displays information every time a new sentence is correctly encoded.
  if (WiFi.status() == WL_CONNECTED) {
    BearSSL::WiFiClientSecure client;
    client.setInsecure();
    HTTPClient https;
    //-------------------------------------------
   
          if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
            Serial.println("okay");
            mfrc522.PCD_StopCrypto1();
            for (byte j = 0; j < 4; j++) {
               while (ss.available() > 0) {
      if (gps.encode(ss.read())) {
        if (gps.location.isUpdated()) {
              Serial.println(F("**Card Detected:**"));
              Serial.print(F("Product Name: "));
              byte buffer2[18];
              block = 60;

              status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, block, &key, &(mfrc522.uid)); //line 834
              if (status != MFRC522::STATUS_OK) {
                Serial.print(F("Authentication failed: "));
                Serial.println(mfrc522.GetStatusCodeName(status));
                return;
              }

              status = mfrc522.MIFARE_Read(block, buffer2, &len);
              if (status != MFRC522::STATUS_OK) {
                Serial.print(F("Reading failed: "));
                Serial.println(mfrc522.GetStatusCodeName(status));
                return;
              }
              // String Product = String str((char *)buffer2);
              // Serial.println(Product);
              //PRINT LAST NAME
              for (uint8_t i = 0; i < 7; i++) {
                Serial.write(buffer2[i] );
                pproduct += (char)buffer2[i];
              }
              Serial.println("");
              Serial.println(pproduct);
              //   pproduct = "";

              Serial.println(F("\n**End Reading**\n"));
              delay(1000); //change value if you want to read cards faster
              bitt = false;
              if (bitt == false) {
                if (++fixCount >= 1) {

                  latit = gps.location.lat();
                  longi = gps.location.lng();
                  Serial.print("Latitude= ");
                  Serial.print(latit, 6);
                  Serial.print(" Longitude= ");
                  Serial.println(longi, 6);
                  //Check WiFi connection status
                  String llatit = String(latit, 6);
                  String llongi = String(longi, 6);



                  // Prepare your HTTP POST request data
                  String httpRequestData = "api_key=" + apiKeyValue + "&product=" + pproduct + "&latitude=" + llatit + "&longitude=" + llongi + "";
                  Serial.print("httpRequestData: ");
                  Serial.println(httpRequestData);
                  delay (500);
                  if (updateserver == false) {

                    Serial.println("conntecting to server..");
                    if (https.begin(client, host, port, path)) {
                      // Specify content-type header
                      https.addHeader("Content-Type", "application/x-www-form-urlencoded");
                      int httpsCode = https.POST(httpRequestData);
                      if (httpsCode > 0) {
                        Serial.println(httpsCode);
                        if (httpsCode == HTTP_CODE_OK) {
                          Serial.println(https.getString());
                          pproduct = "";
                        }
                      } else {
                        Serial.print("failed to POST");
                        updateserver = false;
                      }
                    } else {
                      Serial.print("failed to connect to server");
                      updateserver = false;
                    }
                  }
                  fixCount = 0;

                }
              }
              }//if location updated
      }//if gpsencode
    }//while ss
              j = 0;
            }//for byte j
          }//if new card present
          else {
            //the gps device would start executing if no new card is present
          }
        
  }// if wifi status
}
//*****************************************************************************************//
