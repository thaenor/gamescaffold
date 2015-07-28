select ti.id, ti.title, ti.user_id, us.first_name, us.last_name, sl.name AS sla_name, tp.name AS priority, 
tp.id AS priority_id, ts.name AS ticket_state, ti.timeout, ti.create_time AS cretime, ti.change_time AS chgtime,
q.group_id
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts, queue q, groups g
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id 
AND q.group_id = g.id AND ti.queue_id = q.id AND ti.id>=202324 order by ti.id;