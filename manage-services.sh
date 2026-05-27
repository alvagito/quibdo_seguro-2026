#!/bin/bash

# Script para gestionar los microservicios
# Uso: bash manage-services.sh [start|stop|restart|status|logs]

COMMAND=${1:-status}
SERVICE=${2:-all}
LOGS_DIR="/tmp/quibdo-services"

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

case "$COMMAND" in
    start)
        echo -e "${BLUE}Iniciando servicios...${NC}"
        bash start-services.sh
        ;;
    
    stop)
        echo -e "${YELLOW}Deteniendo servicios...${NC}"
        killall php 2>/dev/null && echo -e "${GREEN}✅ Servicios detenidos${NC}" || echo -e "${RED}No hay servicios corriendo${NC}"
        ;;
    
    restart)
        echo -e "${YELLOW}Reiniciando servicios...${NC}"
        killall php 2>/dev/null
        sleep 1
        bash start-services.sh
        ;;
    
    status)
        echo -e "${BLUE}Estado de servicios:${NC}"
        echo ""
        pgrep -l "php" | grep -E "artisan|serve" || echo -e "${RED}No hay servicios corriendo${NC}"
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
