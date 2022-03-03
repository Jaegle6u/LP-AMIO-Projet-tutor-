#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>

#include <Wire.h>
#include <Adafruit_Sensor.h>

#include <DHT.h>
#include <DHT_U.h>

#define USE_SERIAL Serial

//Variable à changer si changement de Plant
String sensorName = "4";//Identifiant de l'Arrosage du Plant
String sensorLocation = "Serre 1 | Persil";//Mettre "Nom Serre" | "espece du plant" auquel est rattaché le systeme d'arrosage (Exemple : "Serre 1 | Tomate" ).Les espaces sont à respecter !!!!!




//Information sur le capteur de temperature*************
#define DHTPIN 15     
#define DHTTYPE    DHT11
DHT_Unified dht(DHTPIN, DHTTYPE);
uint32_t delayMS;


//*********Capteur humidite du sol***************
#define SensorHSPin A0

//******************Relay de la pompe*******
const int relay = 4;

// *****************WIFI et HTTP**********
const char* ssid     = "raspi-webgui-loic";
const char* password = "ChangeMe";
#define CONNECTION_TIMEOUT 40
int timeout_counter = 0;
// REPLACE with your Domain name and URL path or IP address with path
//const char* serverName = "http://loicjaegle.000webhostapp.com/serveur.php";
const char* serverName = "http://192.168.50.126/esp32/serveur.php";//serveur local


// Keep this API Key value to be compatible with the PHP code provided in the project page. 
// If you change the apiKeyValue value, the PHP file /post-esp-data.php also needs to have the same key 
String apiKeyValue = "tPmAT5Ab3j7F96";



//**********Pour recuperer JSON de l'API
String jsonBuffer;

//****************Arrosage******
//Pour eviter d'allumer la pompe si la requete GET a echoué...
bool checkTemperature = false;
bool checkHumidite = false;
bool checkHumiditeSol = false;

int minTemperature =0;
int maxTemperature =0;

int minHumidite =0;
int maxHumidite =0;

int minHumiditeSol =0;
int maxHumiditeSol =0;

//************************SETUP********************
void setup() {
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(200);
    Serial.print(".");
    timeout_counter++;
    if(timeout_counter >= CONNECTION_TIMEOUT){
        ESP.restart();
      }
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  //SETUP DHT
  dht.begin();
  sensor_t sensor;
  dht.temperature().getSensor(&sensor);
  delayMS = sensor.min_delay / 1000;

  //Setup RELAY
  pinMode(relay, OUTPUT);

  
}//*****************FIN SETUP***************************

void loop() {
  //PARTIE DHT *************************
    // Delay between measurements.
  delay(delayMS);
  // Get temperature event and print its value.
  sensors_event_t event;
 dht.temperature().getEvent(&event);
 int temp = event.temperature;
   dht.humidity().getEvent(&event);
   int hum = event.relative_humidity;
  Serial.print(F("Temperature: "));
    Serial.print(temp);
    Serial.println(F("°C"));
   Serial.print(F("Humidity: "));
    Serial.print(hum);
    Serial.println(F("%"));
    
    //Partie capteur Humidite Sol**************
    float valueHS= analogRead(SensorHSPin);
    Serial.print("Moisture : ");
    valueHS = (valueHS/4096.00)*100;
    Serial.print(valueHS);
    Serial.println("%");
    delay(1000);
    
  
  
  
    
  //Check WiFi connection status ********************
  if(WiFi.status()== WL_CONNECTED){
    WiFiClient client;
    HTTPClient http;
    
    // Your Domain name with URL path or IP address with path
    http.begin(client, serverName);
    
    // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Prepare your HTTP POST request data
    String httpRequestData = "api_key=" + apiKeyValue + "&sensor=" + sensorName
                          + "&location=" + sensorLocation + "&value1=" + String(temp)
                          + "&value2=" + String(hum) +"&value3=" + String(valueHS)+ "";
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);
    
    // You can comment the httpRequestData variable above
    // then, use the httpRequestData variable below (for testing purposes without the BME280 sensor)
    //String httpRequestData = "api_key=tPmAT5Ab3j7F9&sensor=BME280&location=Office&value1=24.75&value2=49.54&value3=1005.14";

    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);
     
    // If you need an HTTP request with a content type: text/plain
    //http.addHeader("Content-Type", "text/plain");
    //int httpResponseCode = http.POST("Hello, World!");
    
    // If you need an HTTP request with a content type: application/json, use the following:
    //http.addHeader("Content-Type", "application/json");
    //int httpResponseCode = http.POST("{\"value1\":\"19\",\"value2\":\"67\",\"value3\":\"78\"}");
        
    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    Serial.println("Fin POST");
    http.end();
    //*****************Partie GET**************
      Serial.println("Requete Api");
      USE_SERIAL.print("[HTTP] begin...\n");
        // configure traged server and url
        http.begin("http://192.168.50.126:8000/api/arrosage.json/4"); //HTTP
      USE_SERIAL.print("[HTTP] GET...\n");
        // start connection and send HTTP header
        int httpCode = http.GET();
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            USE_SERIAL.printf("[HTTP] GET... code: %d\n", httpCode);

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();
                USE_SERIAL.println(payload);
                jsonBuffer = payload;
                Serial.println(jsonBuffer);
                JSONVar myObject = JSON.parse(jsonBuffer);
                if (JSON.typeof(myObject) == "undefined") {
                  Serial.println("Parsing input failed!");
                  return;
                }
              
                //Recuperation Valeur Temperature de l'arrosage
                minTemperature = myObject["minTemperature"];
                Serial.print("minTemperature: ");
                Serial.println(minTemperature);
                maxTemperature = myObject["maxTemperature"];
                Serial.print("maxTemperature: ");
                Serial.println(maxTemperature);
                checkTemperature = myObject["checkTemperature"];
                Serial.print("checkTemperature: ");
                Serial.println(checkTemperature);
                
                //Recuperation Valeur Humidite de l'arrosage
                minHumidite = myObject["minHumidite"];
                Serial.print("minHumidite: ");
                Serial.println(minHumidite);
                maxHumidite = myObject["maxHumidite"];
                Serial.print("maxHumidite: ");
                Serial.println(maxHumidite);
                checkHumidite = myObject["checkHumidite"];
                Serial.print("checkHumidite: ");
                Serial.println(checkHumidite);

                //Recuperation Valeur HumiditeSol de l'arrosage
                minHumiditeSol = myObject["minHumiditeSol"];
                Serial.print("minHumiditeSol: ");
                Serial.println(minHumiditeSol);
                maxHumiditeSol = myObject["maxHumiditeSol"];
                Serial.print("maxHumiditeSol: ");
                Serial.println(maxHumiditeSol);
                checkHumiditeSol = myObject["checkHumiditeSol"];
                Serial.print("checkHumiditeSol: ");
                Serial.println(checkHumiditeSol);

                
            }
        } else {
            USE_SERIAL.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }
      
    // Free resources
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }


  if((checkTemperature && (minTemperature < temp && maxTemperature > temp)) ||
  (checkHumidite && (minHumidite < hum && maxHumidite > hum)) ||
  (checkHumiditeSol && (minHumiditeSol < valueHS && maxHumiditeSol > valueHS))){
    
    Serial.println("Arrosage allumé");
    digitalWrite(relay, HIGH);
    delay(10000);
    Serial.println("Arrosage eteint");
    digitalWrite(relay, LOW);
    }
   //TEST relay
  //digitalWrite(relay, LOW);
  //Serial.println("Current Flowing");
  //delay(5000);
 // digitalWrite(relay, HIGH);
  //Serial.println("Current not Flowing");
  //delay(5000);
  
  //Send an HTTP POST request every 5 minute
  delay(60000*5);
   
}
