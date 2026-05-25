import requests
import os

URL_API = "http://fleet-it-report.test/api/cctv/receive-snapshot"
file_gambar = "test.jpg"

if not os.path.exists(file_gambar):
    print("ERROR: File 'test.jpg' tidak ditemukan di folder ini!")
    exit()

print("Mencoba mengirim gambar ke server lokal ITSM Stack...")

payload = {
    'lokasi': 'MT. Queen Majesty',
    'label': 'CH-01',
    'folder_target': '2026-05-22'
}

try:
    with open(file_gambar, 'rb') as f:
        files = {'snapshot': f}
        r = requests.post(URL_API, data=payload, files=files, timeout=10)

        if r.status_code == 200:
            print("SUKSES BESAR! Gambar berhasil masuk ke Laravel.")
            print("Response:", r.text)
        else:
            print(f"GAGAL! Kode Error: {r.status_code}")
            print("Response:", r.text)
except Exception as e:
    print(f"Waduh, ada error koneksi: {e}")
