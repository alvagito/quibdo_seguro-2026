#!/bin/bash

<<<<<<< HEAD
# Script para levantar el cliente web y todos los microservicios
=======
# Script para levantar todos los microservicios
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
# Uso: bash start-services.sh

set -e

<<<<<<< HEAD
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
MICROSERVICES_DIR="$ROOT_DIR/microservices"
=======
MICROSERVICES_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/microservices" && pwd)"
SERVICES=("auth-service" "incident-service" "notification-service" "rewards-service" "analytics-service" "gateway")
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489

echo "🚀 Iniciando microservicios de Quibdo Seguro..."
echo "=================================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Crear directorio para logs
LOGS_DIR="/tmp/quibdo-services"
<<<<<<< HEAD
PIDS_DIR="$LOGS_DIR/pids"
MONGODB_DATA_DIR="$LOGS_DIR/mongodb-data"
mkdir -p "$LOGS_DIR"
mkdir -p "$PIDS_DIR"
mkdir -p "$MONGODB_DATA_DIR"

# Limpiar logs anteriores
rm -f "$LOGS_DIR"/*.log
rm -f "$PIDS_DIR"/*.pid
=======
mkdir -p "$LOGS_DIR"

# Limpiar logs anteriores
rm -f "$LOGS_DIR"/*.log
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489

# Función para iniciar un servicio
start_service() {
    local service=$1
    local port=$2
<<<<<<< HEAD
    local service_path=$3
    local log_file="$LOGS_DIR/$service.log"
    local pid_file="$PIDS_DIR/$service.pid"
=======
    local service_path="$MICROSERVICES_DIR/$service"
    local log_file="$LOGS_DIR/$service.log"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489

    if [ ! -d "$service_path" ]; then
        echo -e "${RED}❌ Servicio no encontrado: $service_path${NC}"
        return 1
    fi

    echo -e "${YELLOW}⏳ Iniciando $service en puerto $port...${NC}"
    
    cd "$service_path"
    
    # Levantar servidor Laravel en background
<<<<<<< HEAD
    setsid nohup php artisan serve --host=127.0.0.1 --port=$port > "$log_file" 2>&1 &
    local pid=$!
    echo "$pid" > "$pid_file"
=======
    php artisan serve --port=$port > "$log_file" 2>&1 &
    local pid=$!
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
    
    # Esperar a que el servidor esté listo
    sleep 2
    
    if ps -p $pid > /dev/null 2>&1; then
        echo -e "${GREEN}✅ $service iniciado (PID: $pid)${NC}"
        echo "   URL: http://localhost:$port"
        echo "   Log: $log_file"
<<<<<<< HEAD
        echo "   PID: $pid_file"
=======
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
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
<<<<<<< HEAD
        setsid nohup mongod --dbpath "$MONGODB_DATA_DIR" --bind_ip 127.0.0.1 --port 27017 > "$LOGS_DIR/mongodb.log" 2>&1 &
        mongo_pid=$!
        echo "$mongo_pid" > "$PIDS_DIR/mongodb.pid"
        sleep 2

        if ps -p "$mongo_pid" > /dev/null 2>&1; then
            echo -e "${GREEN}✅ MongoDB iniciado (PID: $mongo_pid)${NC}"
        else
            echo -e "${RED}❌ Error al iniciar MongoDB${NC}"
            cat "$LOGS_DIR/mongodb.log"
            exit 1
        fi
=======
        mongod --dbpath /var/lib/mongodb > "$LOGS_DIR/mongodb.log" 2>&1 &
        sleep 2
        echo -e "${GREEN}✅ MongoDB iniciado${NC}"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
    else
        echo -e "${GREEN}✅ MongoDB ya está corriendo${NC}"
    fi
fi

# Iniciar servicios
echo ""
echo -e "${YELLOW}Iniciando servicios...${NC}"
echo ""

<<<<<<< HEAD
start_service "web-client" 8006 "$ROOT_DIR"
start_service "auth-service" 8001 "$MICROSERVICES_DIR/auth-service"
start_service "incident-service" 8002 "$MICROSERVICES_DIR/incident-service"
start_service "rewards-service" 8003 "$MICROSERVICES_DIR/rewards-service"
start_service "notification-service" 8004 "$MICROSERVICES_DIR/notification-service"
start_service "analytics-service" 8005 "$MICROSERVICES_DIR/analytics-service"
start_service "gateway" 8000 "$MICROSERVICES_DIR/gateway"
=======
start_service "auth-service" 8001
start_service "incident-service" 8002
start_service "rewards-service" 8003
start_service "notification-service" 8004
start_service "analytics-service" 8005
start_service "gateway" 8000
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489

echo ""
echo "=================================================="
echo -e "${GREEN}✅ Todos los servicios iniciados correctamente!${NC}"
echo ""
echo "📋 Servicios disponibles:"
<<<<<<< HEAD
echo "   Web Client (Blade):             http://localhost:8006"
echo "   Gateway (Punto de entrada):     http://localhost:8000/api"
=======
echo "   Gateway (Punto de entrada):     http://localhost:8000"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
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
<<<<<<< HEAD
echo "   bash manage-services.sh stop"
=======
echo "   killall php"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
echo ""
