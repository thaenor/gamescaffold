select th.id, tp.name, ts.name, owner_id AS player_id, queue.name AS team_name, th.type_id, tt.name AS type
 from ticket_history th, ticket_priority tp, ticket_state ts, users, queue, ticket_type tt
 where th.priority_id = tp.id 
 AND th.state_id = ts.id 
 AND owner_id = users.id
 AND th.queue_id = queue.id
 AND th.type_id = tt.id
 AND th.id = 7376519
 ORDER BY th.id desc limit 1