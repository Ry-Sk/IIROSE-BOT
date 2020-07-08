IB.addListener(IB.events.ChatEvent,function (e) {
    if(e.message==="喵"){
        IB.sendPacket(
            IB.packets.ChatPacket(
                '喵呜~'
            )
        );
    }
})
var_dump(require("a/c"));