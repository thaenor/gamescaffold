/**
 * Created by NB21334 on 18/05/2015.
 */

// player (user) class representation in Javascript
function player(id, title, name, health_points, experience, level, league) {
    this.id = id;
    this.title = title;
    this.name = name;
    this.health_points = health_points;
    this.experience = experience;
    this.level = level;
    this.league = league;
}


/******************************************************************************/
/* *** Example to create object product ***
 // store (contains the products)
 function store() {
 this.products = [
 new product("APL", "Apple", "Eat one every…", 12, 90, 0, 2, 0, 1, 2),
 new product("AVC", "Avocado", "Guacamole…", 16, 90, 0, 1, 1, 1, 2),
 new product("BAN", "Banana", "These are…", 4, 120, 0, 2, 1, 2, 2),
 // more products…
 new product("WML", "Watermelon", "Nothing…", 4, 90, 4, 4, 0, 1, 1)
 ];
 this.dvaCaption = ["Negligible", "Low", "Average", "Good", "Great" ];
 this.dvaRange = ["below 5%", "between 5 and 10%",… "above 40%"];
 }
 */