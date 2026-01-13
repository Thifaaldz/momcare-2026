# N8N MomCare Chatbot Workflow

Dokumentasi lengkap untuk workflow chatbot AI MomCare yang berjalan di N8N.

## 📋 Deskripsi

Workflow N8N ini berfungsi sebagai **backend AI processing** untuk sistem chatbot kesehatan kehamilan MomCare. Workflow ini menerima permintaan dari aplikasi Laravel, memproses menggunakan AI (Llama 4 Scout via Groq), dan mengembalikan response yang empatik dan informatif untuk ibu hamil.

## 🔄 Alur Kerja (Workflow Flow)

```
┌─────────────┐     ┌───────────────┐     ┌─────────────┐     ┌─────────────────┐     ┌──────────────┐     ┌──────────────────┐
│  Webhook    │────▶│ Normalize     │────▶│   Prompt    │────▶│   AI Agent      │────▶│   Format     │────▶│ Respond to       │
│  (POST)     │     │   Input       │     │   Builder   │     │ (Llama 4 Scout) │     │   Reply      │     │   Webhook        │
└─────────────┘     └───────────────┘     └─────────────┘     └─────────────────┘     └──────────────┘     └──────────────────┘
```

## 📊 Detail Nodes

### 1. Webhook Node
```
Type: n8n-nodes-base.webhook
Version: 2.1
Name: Webhook
Endpoint: POST /webhook/chatbot
Response Mode: responseNode
Webhook ID: 416ed792-5941-4e04-83aa-dc0d1f71fb71
```

**Fungsi:**
- Menerima request POST dari aplikasi Laravel
- Endpoint: `http://n8n:5678/webhook-test/chatbot` (development)
- Endpoint: `http://n8n:5678/webhook/chatbot` (production)

**Format Request yang Diharapkan:**
```json
{
  "session_id": "string",
  "patient": {
    "usia_ibu": "number",
    "usia_kehamilan_minggu": "number",
    // ... data pasien lainnya
  },
  "history": [
    {
      "role": "user|assistant",
      "content": "string"
    }
  ]
}
```

---

### 2. Normalize Input Node
```
Type: n8n-nodes-base.set
Version: 3.4
Name: Normalize Input
Position: [208, 0]
```

**Fungsi:** Merapikan dan menormalisasi data input dari webhook.

**Assignments:**
| Field | Source | Description |
|-------|--------|-------------|
| `patient` | `{{ $json.body.patient }}` | Data lengkap pasien |
| `history` | `{{ $json.body.history || [] }}` | Riwayat chat (array, default empty) |
| `session_id` | `{{ $json.body.session_id }}` | ID sesi chat |

---

### 3. Prompt Builder Node
```
Type: n8n-nodes-base.set
Version: 3.4
Name: prompt
Position: [432, 0]
```

**Fungsi:** Membuat prompt yang dioptimalkan untuk response kesehatan kehamilan.

**Prompt Template:**
```
Aturan utama: 
- Langsung jelaskan kenapa keluhan bisa terjadi pada kehamilan
- Beri reassurance apakah umumnya aman
- Beri saran ringan yang aman
- Tutup dengan maksimal SATU pertanyaan lanjutan
- Jangan interogatif
- Jangan format artikel
- Maksimal 5 kalimat
- Nada empatik seperti bidan berbicara

Data pasien:
- Usia ibu: {usia_ibu} tahun
- Usia kehamilan: {usia_kehamilan_minggu} minggu

Riwayat singkat (konteks):
{history}

Pesan terbaru pasien:
"{latest_message}"
```

**Karakteristik Response AI:**
- ✅ **Empatik**: Nada seperti bidan berbicara
- ✅ **Ringkas**: Maksimal 5 kalimat
- ✅ **Informatif**: Jelaskan penyebab keluhan
- ✅ **Reassuring**: Berikan reassurance keamanan
- ✅ **Actionable**: Saran ringan yang aman untuk ibu hamil
- ✅ **Engaging**: Satu pertanyaan lanjutan (bukan interogatif)

---

### 4. AI Agent Node
```
Type: @n8n/n8n-nodes-langchain.agent
Version: 3.1
Name: AI Agent1
Position: [656, 0]
```

**Fungsi:** Agent AI yang memproses prompt dan menghasilkan response.

**Model yang Digunakan:** Groq Chat Model (Llama 4 Scout)

---

### 5. Groq Chat Model Node
```
Type: @n8n/n8n-nodes-langchain.lmChatGroq
Version: 1
Name: Groq Chat Model1
Position: [624, 240]
```

**Konfigurasi:**
| Parameter | Value |
|-----------|-------|
| Model | `meta-llama/llama-4-scout-17b-16e-instruct` |
| Provider | Groq |
| Credentials | Groq account |

**Spesifikasi Model:**
- **Model Name:** Llama 4 Scout
- **Provider:** Meta
- **Parameters:** 17B parameters, 16K context window
- **Type:** Instruct model (terlatih untuk mengikuti instruksi)

**Keunggulan Model:**
- Response cepat (inference speed tinggi di Groq)
- Context window besar (16K)
- Terlatih untuk instruksi kompleks
- Efisien untuk tugas reasoning

---

### 6. Format Reply Node
```
Type: n8n-nodes-base.set
Version: 3.4
Name: Format Replay
Position: [960, 0]
```

**Fungsi:** Memformat output AI untuk memastikan format yang konsisten.

**Transformasi:**
```javascript
{{$json.output.replace(/\n/g, "\\n")}}
```

**Tujuan:** Mengescape karakter newline untuk JSON response yang valid.

---

### 7. Respond to Webhook Node
```
Type: n8n-nodes-base.respondToWebhook
Version: 1.5
Name: Respond to Webhook
Position: [1168, 0]
```

**Fungsi:** Mengirimkan response balik ke aplikasi pemanggil.

**Response Format:**
```json
{
  "reply": "{ai_response}"
}
```

---

## 🔗 Koneksi (Connections)

```
Webhook [main] ──────────────▶ Normalize Input [main]

Normalize Input [main] ──────▶ prompt [main]

prompt [main] ───────────────▶ AI Agent1 [main]

AI Agent1 [main] ────────────▶ Format Replay [main]

Format Replay [main] ────────▶ Respond to Webhook [main]

Groq Chat Model1 [ai_languageModel] ──▶ AI Agent1 [ai_languageModel]
```

---

## ⚙️ Konfigurasi Environment

### Development
```
N8N_WEBHOOK_URL = 'http://n8n:5678/webhook-test/chatbot'
```

### Production
```
N8N_WEBHOOK_URL = 'http://n8n:5678/webhook/chatbot'
```

---

## 🔐 Credentials

### Groq API
- **Type:** Groq API Credentials
- **Credential ID:** `49ruadeJqqYvo9cV`
- **Name:** Groq account

**Cara Setup:**
1. Buka N8N Settings > Credentials
2. Buat credential baru dengan type "Groq API"
3. Masukkan API key dari [Groq Console](https://console.groq.com)
4. Simpan dan hubungkan ke node "Groq Chat Model1"

---

## 📝 Contoh Interaksi

### Request Contoh
```json
{
  "session_id": "123e4567-e89b-12d3-a456-426614174000",
  "patient": {
    "usia_ibu": 28,
    "usia_kehamilan_minggu": 16
  },
  "history": [
    {
      "role": "user",
      "content": "Dok, saya merasa mual всё время"
    },
    {
      "role": "assistant", 
      "content": "Mual adalah keluhan yang sangat umum di awal kehamilan, Bu. Ini terjadi karena perubahan hormon..."
    },
    {
      "role": "user",
      "content": "Apakah ini berbahaya untuk bayi saya?"
    }
  ]
}
```

### Response Contoh
```json
{
  "reply": "Mual yang ibu rasakan adalah hal yang sangat normal di kehamilan 16 minggu, Bu. Ini sebenarnya pertanda bahwa plasenta berkembang dengan baik dan menghasilkan hormon-hormon yang dibutuhkan bayi. Selama ibu masih bisa makan dan minum sedikit-sedikit, kondisi ini umumnya tidak berbahaya. Coba makan dalam porsi kecil tapi sering, dan hindari perut kosong ya. Apakah ibu sudah mencobaNGOBROLIN remedies tertentu untuk meredakan mual?"
}
```

---

## 🏥 Panduan Medis (Guidelines)

### Aturan Penting untuk AI:
1. **JANGAN** memberikan diagnosis medis
2. **JANGAN** meresepkan obat
3. **JANGAN** menggantikan konsultasi dengan tenaga medis profesional
4. **SELALU** encourage untuk konsultasi ke bidan/dokter jika keluhan serius
5. **FOKUS** pada edukasi dan reassurance umum

### Red Flags yang Harus Diwaspadai:
- Pendarahan vagina
- Demam tinggi (>38°C)
- Nyeri perut hebat
- Ketuban pecah
- Gerakan bayi berkurang
- Kontraksi sebelum minggu 37

**Jika user melaporkan red flags, response harus encourage konsultasi segera.**

---

## 🛠️ Troubleshooting

### Common Issues

| Masalah | Penyebab | Solusi |
|---------|----------|--------|
| Timeout | Response AI terlalu lama | Cek koneksi Groq, tambahkan timeout |
| 500 Error | Format request salah | Validasi JSON request |
| Empty response | Model error | Cek Groq API key |
| Newline errors | Output tidak ter-escape | Cek Format Reply node |

### Logs
- N8N logs: `n8n/data/n8nEventLog.log`
- Execution logs: `n8n/data/n8nEventLog-{n}.log`

---

## 📁 File Terkait

```
momcare/
├── n8n/
│   ├── data/
│   │   ├── database.sqlite      # N8N database
│   │   └── nodes/               # Custom nodes
│   └── README.md               # Dokumen ini
├── src/
│   ├── app/
│   │   └── Livewire/
│   │       └── Chatbot.php      # Laravel integration
│   └── config/
│       └── services.php         # N8N webhook URL config
└── docker-compose.yml
```

---

## 🔄 Cara Import Workflow

1. Buka N8N Dashboard
2. Klik "Add Workflow" > "Import from JSON"
3. Copy-paste JSON workflow dari file ini
4. Atur credentials (Groq API)
5. Activate workflow

---

## 📈 Monitoring

### Metrics yang Perlu Dipantau:
- **Response time**: Target < 5 detik
- **Success rate**: Target > 95%
- **Token usage**: Untuk optimasi cost

### Alerts:
- Jika response time > 30 detik
- Jika error rate > 10%

---

## 🚀 Deployment

### Development
```bash
# N8N berjalan di http://localhost:5678
docker-compose up -d n8n
```

### Production
```bash
# Pastikan environment variables ter-set
docker-compose up -d n8n

# Aktifkan HTTPS dengan Nginx
```

---

## 📄 Lisensi

Dokumen ini adalah bagian dari proyek MomCare.

