# ====================================================================
# AGENT ITAM AUTO-DISCOVERY - BULLETPROOF VERSION
# ====================================================================

$ApiUrl = "http://fleet-it-report.test/api/v1/agent/sync-asset"
$SecretToken = "Amarin-ITSM-Super-Secret-Token-2026"

Write-Host "Memindai perangkat..." -ForegroundColor Cyan

$ComputerSystem = Get-CimInstance Win32_ComputerSystem
$OS = Get-CimInstance Win32_OperatingSystem
$BIOS = Get-CimInstance Win32_BIOS
$Processor = Get-CimInstance Win32_Processor | Select-Object -First 1
$Disk = Get-CimInstance Win32_LogicalDisk | Where-Object DeviceID -eq "C:"

$MacAddress = (Get-CimInstance Win32_NetworkAdapterConfiguration | Where-Object { $_.MACAddress -ne $null } | Select-Object -First 1).MACAddress
$ComputerName = $env:COMPUTERNAME
$IPAddress = (Get-NetIPAddress -AddressFamily IPv4 -InterfaceAlias "Wi-Fi", "Ethernet*", "vEthernet*" -ErrorAction SilentlyContinue | Select-Object -First 1).IPAddress

Write-Host "Terdeteksi MAC: $MacAddress" -ForegroundColor Green
Write-Host "Terdeteksi Nama: $ComputerName" -ForegroundColor Green

# Metrik Hardware
$Storage = [math]::Round(($Disk.Size) / 1GB, 2)
$RAM = [math]::Round($ComputerSystem.TotalPhysicalMemory / 1GB, 2)
$LastBoot = $OS.LastBootUpTime.ToString("yyyy-MM-dd HH:mm:ss")
$DiskUsage = [math]::Round((($Disk.Size - $Disk.FreeSpace) / $Disk.Size) * 100, 2)
$FreeRAM = $OS.FreePhysicalMemory * 1KB
$RAMUsage = [math]::Round((($ComputerSystem.TotalPhysicalMemory - $FreeRAM) / $ComputerSystem.TotalPhysicalMemory) * 100, 2)

$CPUUsageObj = Get-WmiObject Win32_Processor | Measure-Object -Property LoadPercentage -Average
$CPUUsage = if ($null -ne $CPUUsageObj.Average) { $CPUUsageObj.Average } else { 0 }

[array]$SoftwareList = Get-ItemProperty HKLM:\Software\Microsoft\Windows\CurrentVersion\Uninstall\*, HKLM:\Software\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall\* |
                Where-Object DisplayName -ne $null |
                Select-Object -ExpandProperty DisplayName -Unique

# 👇 KUNCI RAHASIA: Memaksa semua data teks dibungkus String ("$...") 👇
$Payload = @{
    mac_address    = "$MacAddress"
    serial_number  = "$($BIOS.SerialNumber)"
    computer_name  = "$ComputerName"
    manufacturer   = "$($ComputerSystem.Manufacturer)"
    model          = "$($ComputerSystem.Model)"
    os_version     = "$($OS.Caption)"
    cpu_model      = "$($Processor.Name)"
    ram_gb         = $RAM
    storage_gb     = $Storage
    ip_address     = "$IPAddress"
    current_user   = "$($ComputerSystem.UserName)"
    last_boot_time = "$LastBoot"
    cpu_usage      = $CPUUsage
    ram_usage      = $RAMUsage
    disk_usage     = $DiskUsage
    software_list  = $SoftwareList
}

# Kompres JSON agar tidak ada spasi/enter yang merusak paket data
$JsonBody = $Payload | ConvertTo-Json -Depth 10 -Compress

# Mengirim ke Server
try {
    # Perhatikan penambahan -ContentType secara eksplisit di bawah ini
    Invoke-RestMethod -Uri $ApiUrl -Method Post -Body $JsonBody -ContentType "application/json; charset=utf-8" -Headers @{
        "Accept" = "application/json"
        "X-Agent-Token" = $SecretToken
    }
    Write-Host "SUKSES: Data Spesifikasi & JSON Berhasil Masuk!" -ForegroundColor Green
} catch {
    Write-Host "GAGAL: Sinkronisasi Ditolak Server." -ForegroundColor Red

    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Penyebab Error: $responseBody" -ForegroundColor Yellow
    } else {
        Write-Host "Penyebab Error: " $_.Exception.Message -ForegroundColor Yellow
    }
}
