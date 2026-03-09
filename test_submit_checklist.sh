#!/bin/bash

# Script de prueba para el endpoint de submit checklist
# Asegúrate de tener:
# 1. El servidor corriendo (php artisan serve)
# 2. Un token de autenticación válido
# 3. Imágenes de prueba en /tmp/

echo "🧪 Probando endpoint: POST /api/v1/checklists/{id}/submit"
echo "=================================================="
echo ""

# Configuración
API_URL="http://localhost:8000/api/v1"
CHECKLIST_ID=1
TOKEN="YOUR_TOKEN_HERE"  # ⚠️ CAMBIAR POR UN TOKEN REAL

# Crear imágenes de prueba si no existen
if [ ! -f "/tmp/test_photo1.jpg" ]; then
    echo "📷 Creando imágenes de prueba..."
    convert -size 800x600 xc:blue -pointsize 50 -fill white -gravity center \
            -annotate +0+0 "Test Photo 1" /tmp/test_photo1.jpg 2>/dev/null || {
        echo "⚠️  ImageMagick no instalado. Usa tus propias imágenes."
    }
fi

# JSON de items
ITEMS='[
  {
    "checklist_item_id": 1,
    "boolean_answer": true
  },
  {
    "checklist_item_id": 2,
    "text_answer": "Todo en orden"
  }
]'

echo "📤 Enviando request..."
echo ""

# Request con cURL
curl -X POST "${API_URL}/checklists/${CHECKLIST_ID}/submit" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" \
  -F "vehicle_id=1" \
  -F "type=entrada" \
  -F "mileage=15250.5" \
  -F "fuel_level=80.0" \
  -F "notes=Prueba desde cURL" \
  -F "items=${ITEMS}" \
  -F "signature_data=iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==" \
  -F "signer_name=Usuario de Prueba" \
  -w "\n\n📊 HTTP Status: %{http_code}\n" \
  -s | jq '.' || echo "⚠️  Instala 'jq' para ver el JSON formateado"

echo ""
echo "=================================================="
echo "✅ Request completado"
echo ""
echo "💡 Tips:"
echo "   - Cambia TOKEN por uno válido"
echo "   - Verifica que el checklist ID existe"
echo "   - Verifica que el vehicle_id existe"
echo "   - Para agregar fotos reales:"
echo "     -F 'photos[]=@/ruta/a/foto1.jpg' \\"
echo "     -F 'photos[]=@/ruta/a/foto2.jpg' \\"
