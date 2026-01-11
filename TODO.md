# TODO - Upload File ke N8N Webhook

## Rencana Implementasi

### 1. Modifikasi Chatbot.php
- [ ] Tambah property `$uploadedFile` untuk menyimpan file yang diupload
- [ ] Tambah method `uploadFile()` untuk upload file ke server Laravel
- [ ] Modifikasi `sendMessage()` untuk mengirim file ke N8N
- [ ] Tambahkan data patient lengkap ke webhook

### 2. Modifikasi chatbot.blade.php
- [ ] Tambah button upload file (paperclip/camera icon)
- [ ] Tambah input file tersembunyi
- [ ] Tampilkan preview file yang akan dikirim
- [ ] Tambah tombol hapus file yang diupload

### 3. Modifikasi chat.js
- [ ] Handle file selection
- [ ] Upload file via Livewire upload
- [ ] Tampilkan progress/preview file

### 4. Struktur JSON ke N8N
```json
{
  "message": "Keluhan user",
  "file": {
    "name": "nama-file.jpg",
    "type": "image/jpeg",
    "size": 102400,
    "url": "http://app:8080/uploads/filename.jpg",
    "base64": "...base64 data..."
  },
  "patient": {
    "id": 1,
    "name": "Nama Pasien",
    "age": 25,
    "pregnancy_week": 12,
    "blood_type": "A",
    "weight": 60,
    "height": 160
  },
  "history": [...],
  "session_id": 123
}
```

---

## Progress
- [ ] 1. Modifikasi Chatbot.php
- [ ] 2. Modifikasi chatbot.blade.php
- [ ] 3. Modifikasi chat.js
- [ ] 4. Testing

