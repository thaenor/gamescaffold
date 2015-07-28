select ti.id, 
	ti.title, 
	ti.user_id, 
	us.first_name, 
	us.last_name, 
	q.group_id AS group_id, 
	q.name AS group_name, 
	sl.name AS sla_name, 
	sl.solution_time AS solution_time, /*tells me how long till an sla runs out in minutes*/
	tp.name AS priority,
	/*tp.id AS priority_id,*/ 
	ts.name AS ticket_state, 
	ti.timeout, /*unix timestamp to when ticket was created*/
	ti.create_time AS cretime, 
	ti.change_time AS chgtime
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts, queue q, groups g
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id
	AND q.group_id = g.id AND ti.queue_id = q.id AND ti.id>=206157 order by ti.id;

/*select * from ticket, ticket_history where ticket.id=ticket_history.ticket_id AND ticket.tn LIKE '2015050410000401';*/
/*select * from users where id=385;*/