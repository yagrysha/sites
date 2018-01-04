module.exports = function (app) {
	require("./express")(app);
	require("./mongoose");
    require("./passport");
};