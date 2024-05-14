<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //* ROLES:
        $role1=Role::create(['name'=>'Administrador']);
        $role2=Role::create(['name'=>'Jefe de área']);
        $role3=Role::create(['name'=>'Técnico de soporte']);
        $role4=Role::create(['name'=>'Usuario estándar']);

        //*PERMISOS:

        //*-------------------------------MODO ADMINISTRADOR------------------------------
        //1) Gestion de todos usuarios
        $permission = Permission::create(['name' => 'usuarios.index'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.edit'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.update'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'usuarios.show'])->syncRoles([$role1]);

        //2) Gestion de areas
        $permission = Permission::create(['name' => 'areas.index'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'areas.edit'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'areas.update'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'areas.show'])->syncRoles([$role1]);

        //3) Gestion de Prioridades
        $permission = Permission::create(['name' => 'prioridades.index'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'prioridades.edit'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'prioridades.update'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'prioridades.show'])->syncRoles([$role1]);

        //4) Asignar area a los usuario tecnicos y jefes de area
        $permission = Permission::create(['name' => 'asignar_area'])->syncRoles([$role1]);
        $permission = Permission::create(['name' => 'actualizar_area'])->syncRoles([$role1]);

        //4) Ver todos los tickets reportados por los clientes
        $permission = Permission::create(['name' => 'tickets.index'])->syncRoles([$role1]);

        //5) Ver todos los comentarios de los clientes

        //6) Analisis de tickets

        //7) Grafico de rendimientos

 
        //*----------------------MODO ADMINISTRADOR Y JEFE DE AREA--------------------------------------
        //1) Asignar ticket a un tecnico 
        $permission = Permission::create(['name' => 'asignar_tecnico_ticket'])->syncRoles([$role1,$role2]);


        //*--------------MODO TECNICO ADMINISTRADOR, JEFE DE AREA Y TECNICO DE SOPORTE-------------------
        //1)ver los tickets asignados  
        $permission = Permission::create(['name' => 'misTickets'])->syncRoles([$role1,$role2,$role3]);

        //2) Ver detalles de los ticket asignados
        $permission = Permission::create(['name' => 'detalles_ticket'])->syncRoles([$role1,$role2,$role3]);

        //3) Respuesta de ticket
        $permission = Permission::create(['name' => 'form_Respuestaticket'])->syncRoles([$role1,$role2,$role3]);
        $permission = Permission::create(['name' => 'guardar_respuestaTicket'])->syncRoles([$role1,$role2,$role3]);

        //4) Pedir mas información del ticket
        $permission = Permission::create(['name' => 'masInfo'])->syncRoles([$role1,$role2,$role3]);
        $permission = Permission::create(['name' => 'guardar_masInfo'])->syncRoles([$role1,$role2,$role3]);



        //*----------------------MODO JEFE DE AREA Y TECNICO DE SOPORTE----------------------------------
        // 1) Ver los ticket de su area 
        $permission = Permission::create(['name' => 'areaUsuario_tickets'])->syncRoles([$role2,$role3]);

        //2) Elegir un ticket de area que esta sin asignar
         $permission = Permission::create(['name' => 'asignar_ticket'])->syncRoles([$role2,$role3]);

        //3) Ver los tickets de area no asignados  
        $permission = Permission::create(['name' => 'tickets_noasignados'])->syncRoles([$role2,$role3]);

        //4) Ver los tickets de area abiertos 
        $permission = Permission::create(['name' => 'tickets_abiertos'])->syncRoles([$role2,$role3]);

        //5) Ver los tickets de area en espera
        $permission = Permission::create(['name' => 'tickets_enEspera'])->syncRoles([$role2,$role3]);

        //6) Ver los tickets de area resueltos
        $permission = Permission::create(['name' => 'tickets_resueltos'])->syncRoles([$role2,$role3]);

        //7) Ver los tickets de area cerrados

        //8) Ver los tecnicos y sus ticket asignados (abiertos, en espera) 
        $permission = Permission::create(['name' => 'tecnicos_tktAsignados'])->syncRoles([$role2,$role3]);

       
        //*------------------------------- MODO JEFE DE AREA-----------------------------------------------
        //1)Permiso para cambiar tickets asignado a un tecnico a otro

        //2)Ver los tickets asignados a los tecnicos de su area 
       

        //3)Ver los comentarios de los clientes referentes a la atencion de sus incidencias
        
        //4) Ver todos los agentes tecnicos que pertenecen a su area
        $permission = Permission::create(['name' => 'area_tecnicos'])->syncRoles([$role2]);

        
        //*------------------------MODO ADMINISTRADOR Y USUARIO ESTANDAR (CLIENTES)-----------------------
        //1)Crear Tickets
        $permission = Permission::create(['name' => 'usuarios_tickets.create'])->syncRoles([$role1,$role4]);
        

        //2)Consultas sus tickets reportados
        $permission = Permission::create(['name' => 'usuarios_tickets.index'])->syncRoles([$role1,$role4]);



    }
}
