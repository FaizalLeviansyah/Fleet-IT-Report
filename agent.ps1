# 1. BACA DATA HARDWARE LOKAL SECARA SILENT
$hostname = $env:COMPUTERNAME
$os_info = (Get-CimInstance Win32_OperatingSystem).Caption
$serial_number = (Get-CimInstance Win32_BIOS).SerialNumber
$ip_address = (Test-Connection -ComputerName $hostname -Count 1).IPv4Address.IPAddressToString
$ram_gb = [math]::Round((Get-CimInstance Win32_ComputerSystem).TotalPhysicalMemory / 1GB)

# 2. BUNGKUS DATA MENJADI JSON
$payload = @{
    "hostname" = $hostname
    "os_version" = $os_info
    "serial_number" = $serial_number
    "ip_address" = $ip_address
    "ram" = "$ram_gb GB"
    "last_seen" = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
} | ConvertTo-Json

# 3. TEMBAKKAN KE API ITSM STACK KITA
$apiUrl = "http://fleet-it-report.test/api/itam/check-in"
Invoke-RestMethod -Uri $apiUrl -Method Post -Body $payload -ContentType "application/json"
