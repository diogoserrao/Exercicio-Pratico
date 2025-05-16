<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cidades = [
            ['nome' => 'Lisboa', 'pais' => 'Portugal', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Porto', 'pais' => 'Portugal', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Faro', 'pais' => 'Portugal', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Madrid', 'pais' => 'Espanha', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Paris', 'pais' => 'França', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Fuchal', 'pais' => 'Portugal', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('cidades')->insert($cidades);

        // Inserir dados na tabela de voos
        $voos = [
            [
                'numero_voo' => 'TP1234',
                'data' => '2025-06-15',
                'origem_id' => 1,
                'destino_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_voo' => 'TP2345',
                'data' => '2025-06-20',
                'origem_id' => 2,
                'destino_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_voo' => 'TP3456',
                'data' => '2025-06-25',
                'origem_id' => 1,
                'destino_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_voo' => 'TP4567',
                'data' => '2025-06-30',
                'origem_id' => 2,
                'destino_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('voo')->insert($voos);

        // Inserir dados na tabela de passageiros
        $passageiros = [
            [
                'nome' => 'João Silva',
                'identificacao' => '123456789',
                'nif' => '123456789',
                'email' => 'joao@example.com',
                'telefone' => '912345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Maria Santos',
                'identificacao' => '234567890',
                'nif' => '234567890',
                'email' => 'maria@example.com',
                'telefone' => '923456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'António Ferreira',
                'identificacao' => '345678901',
                'nif' => '345678901',
                'email' => 'antonio@example.com',
                'telefone' => '934567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Ana Pereira',
                'identificacao' => '456789012',
                'nif' => '456789012',
                'email' => 'ana@example.com',
                'telefone' => '945678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('passageiro')->insert($passageiros);

        // Inserir dados na tabela de reservas
        $reservas = [
            [
                'numero_reserva' => 'RES001',
                'preco' => 120.00, // 120.00€ (em centavos)
                'voo_id' => 1, // TP1234
                'passageiro_id' => 1, // João Silva
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_reserva' => 'RES002',
                'preco' => 150.00, // 150.00€ (em centavos)
                'voo_id' => 2, // TP2345
                'passageiro_id' => 2, // Maria Santos
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_reserva' => 'RES003',
                'preco' => 250.00, // 250.00€ (em centavos)
                'voo_id' => 3, // TP3456
                'passageiro_id' => 3, // António Ferreira
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_reserva' => 'RES004',
                'preco' => 300.00, // 300.00€ (em centavos)
                'voo_id' => 4, // TP4567
                'passageiro_id' => 4, // Ana Pereira
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'numero_reserva' => 'RES005',
                'preco' => 120.00, // 120.00€ (em centavos)
                'voo_id' => 1, // TP1234
                'passageiro_id' => 2, // Maria Santos
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reserva')->insert($reservas);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpar as tabelas na ordem inversa para respeitar as restrições de chave estrangeira
        DB::table('reserva')->truncate();
        DB::table('passageiro')->truncate();
        DB::table('voo')->truncate();
        DB::table('cidades')->truncate();
    }
};
