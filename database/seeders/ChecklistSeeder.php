<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $checklist = Checklist::create([
            'name' => 'Inspección Vehicular Estándar',
            'version' => '1.0',
            'is_active' => true,
        ]);

        $items = [
            ['label' => 'Luces delanteras funcionando', 'type' => 'boolean', 'required' => true, 'order' => 1],
            ['label' => 'Luces traseras funcionando', 'type' => 'boolean', 'required' => true, 'order' => 2],
            ['label' => 'Neumáticos en buen estado', 'type' => 'boolean', 'required' => true, 'order' => 3],
            ['label' => 'Nivel de aceite', 'type' => 'boolean', 'required' => true, 'order' => 4],
            ['label' => 'Frenos funcionando correctamente', 'type' => 'boolean', 'required' => true, 'order' => 5],
            ['label' => 'Espejos en buen estado', 'type' => 'boolean', 'required' => true, 'order' => 6],
            ['label' => 'Limpiabrisas funcionando', 'type' => 'boolean', 'required' => true, 'order' => 7],
            ['label' => 'Extinguidor presente y vigente', 'type' => 'boolean', 'required' => true, 'order' => 8],
            ['label' => 'Botíquin presente', 'type' => 'boolean', 'required' => true, 'order' => 9],
            ['label' => 'Tríangulos de seguridad', 'type' => 'boolean', 'required' => true, 'order' => 10],
            ['label' => 'Gato y llave de ruedas', 'type' => 'boolean', 'required' => true, 'order' => 11],
            ['label' => 'Condición general de la carrocería', 'type' => 'text', 'required' => false, 'order' => 12],
            ['label' => 'Observaciones adicionales', 'type' => 'text', 'required' => false, 'order' => 13],
            ['label' => 'Foto frontal del vehículo', 'type' => 'photo', 'required' => true, 'order' => 14],
            ['label' => 'Foto trasera del vehículo', 'type' => 'photo', 'required' => true, 'order' => 15],
        ];

        foreach ($items as $item) {
            ChecklistItem::create(array_merge($item, ['checklist_id' => $checklist->id]));
        }
    }
}
