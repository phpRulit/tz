<?php


namespace App\Console\Admin;


use App\Entity\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Смена роли пользователя';

    public function handle()
    {
        //принимаем ввод в e-mail пользователя и роль на которую нцжно сменить существующую
        $email = $this->argument('email');
        $role = $this->argument('role');

        //если пользователь не найден по введённому e-mail, то ошибка
        if (!$user = User::where('email', $email)->first()) {
            $this->error('Пользователь с данным email - ' . $email . ', не найден!!!');
            return false;
        }

        //если пользователь найден, то записываем пользователю новую роль
        try {
            $user->changeRole($role);
        } catch (\DomainException $e) { //если неудача, то кидаем исключение
            $this->error($e->getMessage());
            return false;
        }

        $this->info('Новая роль успешно записана!!!');
        return true;
    }

}
