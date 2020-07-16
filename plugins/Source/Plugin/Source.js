IB.addListener(IB.events.CommandEvent,function (e) {
    if(e.sign==="test:send"){
        IB.sendPacket(
            IB.packets.SourcePacket(
                e.input.getArgument("message")
            )
        );
        IB.sendPacket(
            IB.packets.ChatPacket(
                "已发送"+e.input.getArgument("message")
            )
        );
    }
});