select us.first_name, us.last_name 

from ticket ti 
	join users  us 
	and ticket_priority priority 
	and ticket_sate 	state 
	and sla 			timeleft 

ON (us.id=ti.user_id, priority.id=ti.ticket_priority) 

where ti.create_time >= ti.create_time - interval '7 days' limit 5

/**************************************************************************/
/*** THIS IS WORKING ***/
SELECT * FROM (ticket ti NATURAL INNER JOIN users us ON ti.user_id=us.id) INNER JOIN ticket_priority ti_p ON ti_p.ticket_priority_id = ti_p.id where ti.create_time >= ti.create_time - interval '7 days' limit 5;

/**************************************************************************/

SELECT ticket ti ti.title ticket_priority ti_p ti_p.name ticket_state ti_s ti_s.name sla sl sl.name
FROM (((ticket ti INNER JOIN users us ON ti.user_id=us.id) 
INNER JOIN ticket_priority ti_p  ON ti.ticket_priority_id = ti_p.id)
INNER JOIN ticket_state ti_s ON ti.ticket_priority_id = ti_s.id)
INNER JOIN sla sl ON ti.sla_id = sl.id
where ti.create_time >= ti.create_time - interval '7 days' limit 5;

SELECT ticket ti ti.title users us us.first_name ticket_priority ti_p ti_p.name ticket_state ti_s ti_s.name sla sl sl.name
FROM (((ti INNER JOIN us ON ti.user_id=us.id) 
INNER JOIN ti_p  ON ti.ticket_priority_id = ti_p.id)
INNER JOIN ti_s ON ti.ticket_priority_id = ti_s.id)
INNER JOIN sl ON ti.sla_id = sl.id
where ti.create_time >= ti.create_time - interval '7 days' limit 3;
