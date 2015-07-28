/*
This query will find out when a ticket was firts opened (or closed) whatever state_id 2 means
select * from ticket_history where ticket_id=62 AND state_id=2 order by create_time desc limit 1;
*/

/*
This query will link groups and users through the tickets*/
select t.id AS ticket_id, q.id, q.group_id, g.name from queue q, groups g, ticket t
where q.group_id = g.id AND t.queue_id = q.id;


/*
This query gets... tickets?? IDK why I wrote this
select ti.id, ti.title, ti.user_id from ticket ti limit 3;
*/