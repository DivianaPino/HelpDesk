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

        //5) Ver todos los tickets reportados por los clientes
        $permission = Permission::create(['name' => 'tickets.index'])->syncRoles([$role1]);

        //6) Ver los técnicos de cada área (desde la vista de "areas")
        $permission = Permission::create(['name' => 'area_tecnicos'])->syncRoles([$role1]);

        //8) Analisis de tickets
        $permission = Permission::create(['name' => 'indexAnalisis'])->syncRoles([$role1]);

        //9) Grafico de rendimientos
        $permission = Permission::create(['name' => 'indexGrafico'])->syncRoles([$role1]);

        //9) Reportes
        $permission = Permission::create(['name' => 'reporteTickets'])->syncRoles([$role1]);

        //10) Tickets reportados por el admin
        $permission = Permission::create(['name' => 'admin_tickets_reportados'])->syncRoles([$role1]);

        //11) Tickets generales (en el menu del admin)
        $permission = Permission::create(['name' => 'admin_tickets'])->syncRoles([$role1]);



 
        //*----------------------MODO ADMINISTRADOR Y JEFE DE AREA--------------------------------------
        
        //1) Agentes de area donde pertenece el admin o jefe de área 
        $permission = Permission::create(['name' => 'agentes_area'])->syncRoles([$role1,$role2]);
        
        //2) Asignar ticket a un técnico 
        $permission = Permission::create(['name' => 'reasignar_ticket'])->syncRoles([$role1,$role2]);

        //3) Ver tickets desde la vista de los ticket asignados a los tecnicos(abiertos, en espera) 
        $permission = Permission::create(['name' => 'verTicket'])->syncRoles([$role1,$role2]);

        //4) Tickets que tienen asignado cada técnico
        $permission = Permission::create(['name' => 'tecnicos_tktAsignados'])->syncRoles([$role1,$role2]);

        //5) Ticket abiertos que tiene cada técnico
        $permission = Permission::create(['name' => 'tkt_abierto_tecnico'])->syncRoles([$role1,$role2]);

        //6) Ticket en espera que tiene cada técnico
        $permission = Permission::create(['name' => 'tkt_enEspera_tecnico'])->syncRoles([$role1,$role2]);

        //7) ver todos los tecnicos y los ticket que tienen asignado
        $permission = Permission::create(['name' => 'tecnicos_tkt_asignados'])->syncRoles([$role1,$role2]);


       
        //*--------------MODO TECNICO ADMINISTRADOR, JEFE DE AREA Y TECNICO DE SOPORTE-------------------
        //1) Ver los tickets asignados  
        $permission = Permission::create(['name' => 'misTickets'])->syncRoles([$role1,$role2,$role3]);

        //2) Ver detalles de los ticket asignados
        $permission = Permission::create(['name' => 'detalles_ticket'])->syncRoles([$role1,$role2,$role3]);

        //3) Ver los tickets no asignados
        $permission = Permission::create(['name' => 'tickets_noasignados'])->syncRoles([$role1,$role2,$role3]);

        //4) Ver los ticket abiertos
        $permission = Permission::create(['name' => 'tickets_abiertos'])->syncRoles([$role1,$role2,$role3]);
      
        //5) Formulario - Respuesta de ticket
        $permission = Permission::create(['name' => 'form_Respuestaticket'])->syncRoles([$role1,$role2,$role3]);

        //6) Ver los ticket en Espera
        $permission = Permission::create(['name' => 'tickets_enEspera'])->syncRoles([$role1,$role2,$role3]);

        //7) Ver los ticket en Revisión 
        $permission = Permission::create(['name' => 'tickets_enRevision'])->syncRoles([$role1,$role2,$role3]);

        //8) Ver los ticket en vencidos
        $permission = Permission::create(['name' => 'tickets_vencidos'])->syncRoles([$role1,$role2,$role3]);

        //9) Ver los ticket en resueltos
        $permission = Permission::create(['name' => 'tickets_resueltos'])->syncRoles([$role1,$role2,$role3]);

        //10) Ver los ticket en reabietos
        $permission = Permission::create(['name' => 'tickets_reabiertos'])->syncRoles([$role1,$role2,$role3]);

        //11) Ver los ticket en cerrados
        $permission = Permission::create(['name' => 'tickets_cerrados'])->syncRoles([$role1,$role2,$role3]);

        //12) Ver comentario de un ticket
        $permission = Permission::create(['name' => 'ver_comentario'])->syncRoles([$role1,$role2,$role3]);

        //13) Ver los técnicos que pertenecen al área del técnico autenticado 
        $permission = Permission::create(['name' => 'todos_tecnicos'])->syncRoles([$role1,$role2,$role3]);

        

        //*----------------------MODO JEFE DE AREA Y TECNICO DE SOPORTE----------------------------------
        // 1) Ver los ticket de su area 
        $permission = Permission::create(['name' => 'areaUsuario_tickets'])->syncRoles([$role2,$role3]);

        //2) Elegir un ticket de area que esta sin asignar
        $permission = Permission::create(['name' => 'asignar_ticket'])->syncRoles([$role2,$role3]);

        //3) Tickets generales (en el menu del jefe de A. y tecnico de soporte)
        $permission = Permission::create(['name' => 'jefe_tec_tickets'])->syncRoles([$role2,$role3]);



        //*------------------------MODO ADMINISTRADOR Y USUARIO ESTANDAR (CLIENTES)-----------------------
        //1)Crear Tickets
        $permission = Permission::create(['name' => 'usuarios_tickets.create'])->syncRoles([$role1,$role4]);
        
        //*-------------------------------USUARIO ESTANDAR (CLIENTES)-------------------------------------

        //1)Consultas sus tickets reportados
        $permission = Permission::create(['name' => 'usuarios_tickets.index'])->syncRoles([$role4]);


        //*--------------------------------------MODO TODOS LOS ROLES--------------------------------------

        //1) Ver calificaciones de las asistencias
        $permission = Permission::create(['name' => 'calificaciones'])->syncRoles([$role1,$role2,$role3,$role4]);



    }
}
