#!/bin/bash

# Configuración de colores para la salida
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # Sin color

echo -e "${BLUE}===============================================${NC}"
echo -e "${BLUE}   Reiniciando la aplicación Académico Web     ${NC}"
echo -e "${BLUE}===============================================${NC}"

# 1. Navegar al directorio del proyecto
DIR="/home/azureuser/academico-web"
if [ -d "$DIR" ]; then
    cd "$DIR"
    echo -e "${GREEN}[OK]${NC} Cambiado al directorio: $DIR"
else
    echo -e "${RED}[ERROR]${NC} No se pudo encontrar el directorio $DIR"
    exit 1
fi

# 2. Limpiar la caché de Laravel
echo -e "\n${YELLOW}Limpiando la caché de Laravel...${NC}"
php artisan optimize:clear

# 3. Reiniciar los workers de cola (en caso de que se utilicen)
echo -e "\n${YELLOW}Reiniciando workers de colas Laravel...${NC}"
php artisan queue:restart

# 4. Reiniciar servicios del sistema (requiere privilegios sudo)
echo -e "\n${YELLOW}Reiniciando servicios PHP-FPM y Nginx...${NC}"

if sudo systemctl restart php8.3-fpm; then
    echo -e "${GREEN}[OK]${NC} PHP-FPM 8.3 reiniciado con éxito."
else
    echo -e "${RED}[ERROR]${NC} Falló el reinicio de PHP-FPM 8.3."
fi

if sudo systemctl restart nginx; then
    echo -e "${GREEN}[OK]${NC} Nginx reiniciado con éxito."
else
    echo -e "${RED}[ERROR]${NC} Falló el reinicio de Nginx."
fi

echo -e "\n${GREEN}===============================================${NC}"
echo -e "${GREEN}      ¡Reinicio y limpieza completados!        ${NC}"
echo -e "${GREEN}===============================================${NC}"
