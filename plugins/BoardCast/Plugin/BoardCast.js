IB.addListener(IB.events.CommandEvent,function (e) {
    if(e.sign==="boardcast:send"){
        IB.sendPacket(
            IB.packets.BoardCastPacket(
                e.input.getArgument("message"),
                e.sender.getColor()
            )
        );
    }
})