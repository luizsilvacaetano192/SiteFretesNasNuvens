#!/bin/bash

# URL da sua API
URL="https://r4xl039zf8.execute-api.us-east-1.amazonaws.com/teste"

# Arquivo de log
LOG_FILE="/var/log/api_curl.log"

# Timeout para o curl (em segundos)
TIMEOUT=60

# Nome do processo (usado para matar travados)
PROCESS_NAME="curl"

# Realiza a chamada com timeout e grava no log com data/hora
{
  echo "----- $(date '+%Y-%m-%d %H:%M:%S') -----"
  timeout $TIMEOUT curl -X GET "$URL"
  echo -e "\n"
} >> "$LOG_FILE" 2>&1

# Verifica e mata processos curl travados há mais de 1 minuto
# (ajuste o tempo conforme necessário)
ps -eo pid,etime,cmd | grep "$PROCESS_NAME" | grep -v grep | while read pid etime cmd; do
  # Se o processo estiver travado por mais de 1 minuto (ex: 00:01:10)
  if [[ "$etime" =~ ([0-9]+)-.* || "$etime" =~ ^[0-9]{2}:[0-9]{2}:[0-9]{2}$ || "$etime" =~ ^[0-9]{2}:[0-9]{2}$ ]]; then
    echo "Matando processo travado: PID=$pid CMD=$cmd" >> "$LOG_FILE"
    kill -9 "$pid"
  fi
done

