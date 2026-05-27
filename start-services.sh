#!/bin/bash

# Script para levantar todos los microservicios
# Uso: bash start-services.sh

set -e

MICROSERVICES_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/microservices" && pwd)"
SERVICES=("auth-service" "incident-service" "notification-service" "rewards-service" "analytics-service" "gateway")

echo "🚀 Iniciando microservicios de Quibdo Seguro..."
echo "=================================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Crear directorio para logs
LOGS_DIR="/tmp/quibdo-services"
mkdir -p "$LOGS_DIR"

# Limpiar logs anteriores
rm -f "$LOGS_DIR"/*.log

# Función para iniciar un servicio
start_service() {
    local service=$1
    local port=$2
    local service_path="$MICROSERVICES_DIR/$service"
    local log_file="$LOGS_DIR/$service.log"

    if [ ! -d "$service_path" ]; then
        echo -e "${RED}❌ Servicio no encontrado: $service_path${NC}"
        return 1
    fi

    echo -e "${YELLOW}⏳ Iniciando $service en puerto $port...${NC}"
    
    cd "$service_path"
    
    # Levantar servidor Laravel en background
    php artisan serve --port=$port > "$log_file" 2>&1 &
    local pid=$!
    
    # Esperar a que el servidor esté listo
    sleep 2
    
    if ps -p $pid > /dev/null 2>&1; then
        echo -e "${GREEN}✅ $service iniciado (PID: $pid)${NC}"
        echo "   URL: http://localhost:$port"
        echo "   Log: $log_file"
    else
        echo -e "${RED}❌ Error al iniciar $service${NC}"
        cat "$log_file"
        return 1
    fi
}

# Iniciar MongoDB (si no está corriendo)
echo ""
echo -e "${YELLOW}Verificando MongoDB...${NC}"
if ! command -v mongod &> /dev/null; then
    echo -e "${RED}⚠️  MongoDB no está instalado. Por favor instálalo primero.${NC}"
    echo "   En Ubuntu/Debian: sudo apt-get install -y mongodb"
    echo "   En macOS: brew install mongodb-community"
else
    if ! pgrep -x "mongod" > /dev/null; then
        echo -e "${YELLOW}Iniciando MongoDB...${NC}"
        mongod --dbpath /var/lib/mongodb > "$LOGS_DIR/mongodb.log" 2>&1 &
        sleep 2
        echo -e "${GREEN}✅ MongoDB iniciado${NC}"
    else
        echo -e "${GREEN}✅ MongoDB ya está corriendo${NC}"
    fi
fi

# Iniciar servicios
echo ""
echo -e "${YELLOW}Iniciando servicios...${NC}"
echo ""

start_service "auth-service" 8001
start_service "incident-service" 8002
start_service "rewards-service" 8003
start_service "notification-service" 8004
start_service "analytics-service" 8005
start_service "gateway" 8000

echo ""
echo "=================================================="
echo -e "${GREEN}✅ Todos los servicios iniciados correctamente!${NC}"
echo ""
echo "📋 Servicios disponibles:"
echo "   Gateway (Punto de entrada):     http://localhost:8000"
echo "   Auth Service:                   http://localhost:8001"
echo "   Incident Service:               http://localhost:8002"
echo "   Rewards Service:                http://localhost:8003"
echo "   Notification Service:           http://localhost:8004"
echo "   Analytics Service:              http://localhost:8005"
echo ""
echo "📍 Logs: $LOGS_DIR"
echo ""
echo "Para ver los logs en tiempo real:"
echo "   tail -f $LOGS_DIR/gateway.log"
echo "   tail -f $LOGS_DIR/auth-service.log"
echo ""
echo "Para detener los servicios:"
echo "   killall php"
echo ""
