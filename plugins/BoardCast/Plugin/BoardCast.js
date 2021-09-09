IB.addListener(
    "Bot\\Event\\CommandEvent",
    function (e) {
        if(e.sign==="boardcast:send"){
            IB.php.getStatic("Bot\\Provider\\IIROSE\\IIROSEProvider").instance.packet(
                IB.php.createInstance("Bot\\Provider\\IIROSE\\Packets\\BoardCastPacket",
                    e.input.getArgument("message"))
            );
        }
    });