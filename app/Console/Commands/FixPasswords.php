<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixPasswords extends Command
{
    protected $signature = 'fix:passwords';
    protected $description = 'Corrige senhas que não estão criptografadas com Bcrypt';

    public function handle()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if (!str_starts_with($user->password, '$2y$')) {
                $user->password = Hash::make($user->password);
                $user->save();
                $this->info("Senha criptografada para: {$user->email}");
                $count++;
            }
        }

        $this->info("Concluído! {$count} senhas corrigidas.");
    }
}
