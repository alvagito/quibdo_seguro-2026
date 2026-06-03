#!/bin/bash

# Script para gestionar los microservicios
# Uso: bash manage-services.sh [start|stop|restart|status|logs]

COMMAND=${1:-status}
SERVICE=${2:-all}
<<<<<<< HEAD
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOGS_DIR="/tmp/quibdo-services"
PIDS_DIR="$LOGS_DIR/pids"
=======
LOGS_DIR="/tmp/quibdo-services"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

case "$COMMAND" in
    start)
        echo -e "${BLUE}Iniciando servicios...${NC}"
<<<<<<< HEAD
        bash "$ROOT_DIR/start-services.sh"
=======
        bash start-services.sh
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
        ;;
    
    stop)
        echo -e "${YELLOW}Deteniendo servicios...${NC}"
<<<<<<< HEAD
        if [ ! -d "$PIDS_DIR" ] || ! ls "$PIDS_DIR"/*.pid >/dev/null 2>&1; then
            echo -e "${RED}No hay PIDs registrados en $PIDS_DIR${NC}"
            exit 0
        fi

        for pid_file in "$PIDS_DIR"/*.pid; do
            pid=$(cat "$pid_file")
            service=$(basename "$pid_file" .pid)

            if ps -p "$pid" >/dev/null 2>&1; then
                kill "$pid"
                echo -e "${GREEN}✅ $service detenido (PID: $pid)${NC}"
            else
                echo -e "${YELLOW}$service no estaba corriendo (PID: $pid)${NC}"
            fi

            rm -f "$pid_file"
        done
=======
        killall php 2>/dev/null && echo -e "${GREEN}✅ Servicios detenidos${NC}" || echo -e "${RED}No hay servicios corriendo${NC}"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
        ;;
    
    restart)
        echo -e "${YELLOW}Reiniciando servicios...${NC}"
<<<<<<< HEAD
        bash "$ROOT_DIR/manage-services.sh" stop
        sleep 1
        bash "$ROOT_DIR/start-services.sh"
=======
        killall php 2>/dev/null
        sleep 1
        bash start-services.sh
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
        ;;
    
    status)
        echo -e "${BLUE}Estado de servicios:${NC}"
        echo ""
<<<<<<< HEAD
        if [ -d "$PIDS_DIR" ] && ls "$PIDS_DIR"/*.pid >/dev/null 2>&1; then
            for pid_file in "$PIDS_DIR"/*.pid; do
                pid=$(cat "$pid_file")
                service=$(basename "$pid_file" .pid)

                if ps -p "$pid" >/dev/null 2>&1; then
                    echo -e "${GREEN}✅ $service corriendo (PID: $pid)${NC}"
                else
                    echo -e "${RED}❌ $service detenido (PID: $pid)${NC}"
                fi
            done
        else
            echo -e "${RED}No hay servicios registrados${NC}"
        fi
=======
        pgrep -l "php" | grep -E "artisan|serve" || echo -e "${RED}No hay servicios corriendo${NC}"
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
        echo ""
        echo "MongoDB:"
        pgrep -l "mongod" || echo -e "${RED}MongoDB no está corriendo${NC}"
        ;;
    
    logs)
        if [ -z "$SERVICE" ] || [ "$SERVICE" = "all" ]; then
            echo -e "${BLUE}Mostrando últimas líneas de todos los logs:${NC}"
            echo ""
            for log in "$LOGS_DIR"/*.log; do
                if [ -f "$log" ]; then
                    echo -e "${YELLOW}$(basename $log):${NC}"
                    tail -n 5 "$log"
                    echo ""
                fi
            done
        else
            if [ -f "$LOGS_DIR/$SERVICE.log" ]; then
                tail -f "$LOGS_DIR/$SERVICE.log"
            else
                echo -e "${RED}Log no encontrado para: $SERVICE${NC}"
                ls -la "$LOGS_DIR/"
            fi
        fi
        ;;
    
    *)
        echo "Uso: bash manage-services.sh [start|stop|restart|status|logs]"
        echo ""
        echo "Comandos disponibles:"
        echo "  start           - Inicia todos los servicios"
        echo "  stop            - Detiene todos los servicios"
        echo "  restart         - Reinicia todos los servicios"
        echo "  status          - Muestra el estado de los servicios"
        echo "  logs [servicio] - Muestra logs (opcional: específico de un servicio)"
        echo ""
        echo "Ejemplos:"
        echo "  bash manage-services.sh start"
        echo "  bash manage-services.sh logs gateway"
        echo "  bash manage-services.sh logs auth-service"
        ;;
esac
