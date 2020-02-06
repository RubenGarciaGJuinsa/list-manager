create table if not exists list
(
    id integer constraint
        list_pk primary key autoincrement,
    name varchar(255) not null
);

create table if not exists task
(
    id integer
        constraint task_pk
        primary key autoincrement,
    list_id integer not null
        constraint task_list_id_fk
        references list
        on update restrict on delete restrict,
    name varchar(255) not null
);