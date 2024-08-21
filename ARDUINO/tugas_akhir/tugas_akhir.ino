#include <ESP8266WiFi.h>
#include <DHT.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <ArduinoJson.h>
#include <ESP8266HTTPClient.h>  // Sertakan pustaka HTTPClient
// #include <WiFiUdp.h>
// #include <NTPClient.h>

#define I2C_ADDR 0x27
#define DHTPIN D4
#define DHTTYPE DHT11
#define SOIL_MOISTURE_PIN A0
#define RELAY_PIN D2
#define SDA_PIN D5
#define SCL_PIN D6

const char* ssid = "Menumpang jak m";
const char* password = "modalbah";
const char* server = " 192.168.3.143";
const char* endpoint = "/penyiram_otomatis/data_gabung.php";
const int  serverPort = 80;

WiFiClient client;
// WiFiUDP ntpUDP;
// NTPClient timeClient(ntpUDP, "pool.ntp.org");

DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(I2C_ADDR, 16, 2);

unsigned long previousMillis = 0;
const long interval = 5000;

int soilMoistureValue = 0;
int pompaOn = 45;  // Nilai kelembapan tanah untuk mengaktifkan pompa (basah)
int pompaOff = 55; // Nilai kelembapan tanah untuk mematikan pompa (kering)

bool displaySuhuKelembapan = true;
// const unsigned long PAGI_START = 6 * 60 * 60 * 1000; // 06:00 AM
// const unsigned long SORE_START = 16 * 60 * 60 * 1000; // 04:00 PM

void setup() {
  Serial.begin(115200);
  delay(10);

  Wire.begin(SDA_PIN, SCL_PIN);
  lcd.init();
  lcd.backlight();

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  dht.begin();
  pinMode(SOIL_MOISTURE_PIN, INPUT);
  pinMode(RELAY_PIN, OUTPUT);

  
}

void loop() {
  unsigned long currentMillis = millis();
  // unsigned long sinceMidnight = currentMillis % (24 * 60 * 60 * 1000); // Waktu sejak tengah malam

  // timeClient.update();
  // unsigned long epochTime = timeClient.getEpochTime(); // Waktu epoch (UNIX time)

  float suhu = dht.readTemperature();
  float kelembapan = dht.readHumidity();
  soilMoistureValue = readSoilMoisture();

  if (isnan(suhu) || isnan(kelembapan)) {
    Serial.println("tidak bisa membaca data sensor suhu!");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("sensor suhu eror");
    delay(2000);
    return;
  }

  Serial.print("Suhu: ");
  Serial.print(suhu);
  Serial.println(" *C");
  Serial.print("Kelembapan: ");
  Serial.print(kelembapan);
  Serial.println(" %");
  Serial.print("Tanah: ");
  Serial.print(soilMoistureValue);
  Serial.println(" %");

  // if (sinceMidnight >= PAGI_START && sinceMidnight < PAGI_START + interval) {
  //   // Ini adalah waktu pagi
  //   controlPumpIfDry(); // Kontrol pompa jika kelembapan kering
  // } else if (sinceMidnight >= SORE_START && sinceMidnight < SORE_START + interval) {
  //   // Ini adalah waktu sore
  //   controlPumpIfDry(); // Kontrol pompa jika kelembapan kering
  // } else {
  //   // Di luar waktu pagi dan sore, pastikan pompa mati
  //   Serial.println("Pompa OFF (hanya on hanya pagi dan sore)...");
  //   digitalWrite(RELAY_PIN, HIGH); // Matikan relay pompa
  // }
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;

    if (displaySuhuKelembapan) {
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Suhu: ");
      lcd.print(suhu);
      lcd.print(" C");

      lcd.setCursor(0, 1);
      lcd.print("kelembapan: ");
      lcd.print(kelembapan);
      lcd.print(" %");

      displaySuhuKelembapan = false;
    } else {
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Tanah: ");
      lcd.print(soilMoistureValue);
      lcd.print(" %");

      lcd.setCursor(0, 1);
      if (soilMoistureValue < pompaOn) {
        lcd.print("Pompa: ON ");
      } else if (soilMoistureValue >= pompaOff) {
        lcd.print("Pompa: OFF");
      } else {
        lcd.print("Pompa: ---");
      }

      displaySuhuKelembapan = true;
    }
  }

  controlPump(); // Memanggil fungsi kontrol pompa
  getPumpControlData(); // Memperbarui nilai pompa_on dan pompa_off dari server
  sendToServer(suhu, kelembapan, soilMoistureValue);
  delay(5000); // Delay untuk menjaga interval
}

void sendToServer(float suhu, float kelembapan, int soilMoistureValue) {
  HTTPClient http;

  String url = "http://" + String(server) + endpoint;
  http.begin(client, url);

  // Buat objek JSON untuk menyimpan data
  StaticJsonDocument<200> doc;

  // Masukkan data ke dalam objek JSON
  doc["suhu"] = suhu;
  doc["kelembapan"] = kelembapan;
  doc["kelembapan_tanah"] = soilMoistureValue;

  // Serialisasi JSON ke dalam string
  String postData;
  serializeJson(doc, postData);

  // Kirim data menggunakan metode POST
  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    String response = http.getString();
    Serial.println("Response:");
    Serial.println(response);
  } else {
    Serial.print("Error data tidak terkirim: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}

void getPumpControlData() {
  HTTPClient http;

  String url = "http://" + String(server) + endpoint + "?get"; // Ubah sesuai dengan URL yang tepat
  http.begin(client, url);

  int httpResponseCode = http.GET();

  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);

    String response = http.getString();
    Serial.println("Response:");
    Serial.println(response);

    // Parsing JSON response
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, response);

    if (!error) {
      pompaOn = doc["pompa_on"];
      pompaOff = doc["pompa_off"];

      Serial.print("Received pump control data: pompa_on=");
      Serial.print(pompaOn);
      Serial.print(", pompa_off=");
      Serial.println(pompaOff);
    } else {
      Serial.print("deserializeJson() failed: ");
      Serial.println(error.f_str());
    }
  } else {
    Serial.print("Error in GET request: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}


int readSoilMoisture() {
  int moisture = analogRead(SOIL_MOISTURE_PIN);
  return map(moisture, 1023, 0, 0, 100);
}

void controlPump() {
  if (soilMoistureValue < pompaOn) {
    Serial.println("Pompa ON...");
    digitalWrite(RELAY_PIN, LOW); // Hidupkan relay pompa
  } else if (soilMoistureValue >= pompaOff) {
    Serial.println("Pompa OFF...");
    digitalWrite(RELAY_PIN, HIGH); // Matikan relay pompa
  }
}

// void controlPumpIfDry() {
//   if (soilMoistureValue < pompaOn) {
//     Serial.println("Pompa ON...");
//     digitalWrite(RELAY_PIN, LOW); // Hidupkan relay pompa
//   } else {
//     Serial.println("Pompa OFF...");
//     digitalWrite(RELAY_PIN, HIGH); // Matikan relay pompa
//   }
// }

