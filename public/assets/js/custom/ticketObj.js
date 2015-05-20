/**
 * Created by NB21334 on 18/05/2015.
 */

// ticket class representation in Javascript
function ticket(id, title, user_id, points, priority, state, slaDesc, timeout, created_at) {
    this.id = id;
    this.title = title;
    this.user_id = user_id;
    this.points = points;
    this.priority = priority;
    this.state = state;
    this.slaDesc = slaDesc;
    this.timeout = timeout;
    this.created_at = created_at;
}