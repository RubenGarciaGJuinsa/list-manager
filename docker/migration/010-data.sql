DELETE FROM task;
DELETE FROM list;

INSERT INTO list (id, name) VALUES (1, 'Lista 1');
INSERT INTO list (id, name) VALUES (2, 'Lista 2');
INSERT INTO list (id, name) VALUES (3, 'Lista 3');

INSERT INTO task (id, list_id, name) VALUES (1, 1, 'Primera tarea');
INSERT INTO task (id, list_id, name) VALUES (2, 1, 'Segunda tarea');
INSERT INTO task (id, list_id, name) VALUES (3, 1, 'Otra tarea');
INSERT INTO task (id, list_id, name) VALUES (4, 2, 'Otra tarea de otra lista');
INSERT INTO task (id, list_id, name) VALUES (5, 1, 'Otra tarea más');
INSERT INTO task (id, list_id, name) VALUES (6, 3, 'Tarea');
INSERT INTO task (id, list_id, name) VALUES (7, 3, 'Otra tarea');