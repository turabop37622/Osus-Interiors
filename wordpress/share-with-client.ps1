# Shares the WordPress theme preview through an ngrok link.
# Visitors only see the site: no auto-login, random admin password, wp-admin blocked.
# Run from VS Code terminal:  .\share-with-client.ps1     Stop with Ctrl+C.

$port = 8890
Set-Location $PSScriptRoot

$ngrok = Start-Process ngrok -ArgumentList "http", "$port" -WindowStyle Hidden -PassThru
try {
    $url = $null
    for ($i = 0; $i -lt 30 -and -not $url; $i++) {
        Start-Sleep 1
        try { $url = (Invoke-RestMethod http://127.0.0.1:4040/api/tunnels).tunnels | Where-Object proto -eq 'https' | Select-Object -First 1 -ExpandProperty public_url } catch {}
    }
    if (-not $url) { throw "ngrok did not start. Is another ngrok already running?" }

    Write-Host ""
    Write-Host "  Client link: $url" -ForegroundColor Green
    Write-Host "  (wait until WordPress says it is ready, then share the link)"
    Write-Host ""

    npx --yes @wp-playground/cli@latest server --port=$port --site-url=$url --mount=osus:/wordpress/wp-content/themes/osus --blueprint=blueprint-share.json
}
finally {
    Stop-Process -Id $ngrok.Id -ErrorAction SilentlyContinue
}
