module.exports = {
    setCurator:function(op, cb){
        op.project.setCurator(this.user, cb);
    },
    activateProject:function(op, cb){
        op.project.activate(cb);
    },
    closeProject:function(op, cb){
        op.project.close(cb);
    }
}