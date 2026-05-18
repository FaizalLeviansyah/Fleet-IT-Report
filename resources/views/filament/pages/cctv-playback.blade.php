<x-filament-panels::page>
    <div style="display: flex; flex-wrap: wrap; gap: 24px;">

        <div style="flex: 1 1 65%; min-width: 320px; background-color: #000000; border-radius: 16px; min-height: 500px; position: relative; display: flex; align-items: center; justify-content: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">

            <div style="text-align: center; color: #4B5563;">
                <svg style="width: 64px; height: 64px; margin: 0 auto 16px auto; color: #374151;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <p style="font-size: 1.125rem; font-weight: 600;">Menunggu Sinyal Rekaman...</p>
                <p style="font-size: 0.875rem; margin-top: 8px;">Silakan pilih kamera dan tanggal di panel pencarian.</p>
            </div>

            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 24px; display: flex; gap: 12px; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <button style="background: #2563EB; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    ▶ Play
                </button>
                <button style="background: #374151; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    ⏸ Pause
                </button>
            </div>
        </div>

        <div style="flex: 1 1 30%; min-width: 300px; background-color: #ffffff; border-radius: 16px; padding: 28px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #E5E7EB; height: fit-content;">

            <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 24px; color: #1E3A8A; border-bottom: 2px solid #EFF6FF; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                🔍 Konfigurasi Pencarian
            </h3>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">Pilih Kamera NVR</label>
                    <select style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; background-color: #F9FAFB; color: #1F2937; outline: none;">
                        <option>CH-01 (Kamera Haluan)</option>
                        <option>CH-02 (Kamera Buritan)</option>
                        <option>CH-03 (Kamar Mesin)</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">Tanggal Rekaman</label>
                    <input type="date" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; background-color: #F9FAFB; color: #1F2937; outline: none; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">Waktu Mulai (Jam)</label>
                    <input type="time" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; background-color: #F9FAFB; color: #1F2937; outline: none; box-sizing: border-box;">
                </div>

                <button style="width: 100%; background-color: #2563EB; color: white; font-weight: bold; padding: 14px; border-radius: 8px; border: none; cursor: pointer; margin-top: 16px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);">
                    Tarik & Putar Rekaman
                </button>
            </div>

        </div>

    </div>
</x-filament-panels::page>
