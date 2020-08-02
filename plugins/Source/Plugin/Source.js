IB.addListener(
    "Bot\\Event\\CommandEvent",
    function (e) {
        if(e.sign==="test:send"){
            IB.php.getStatic("Bot\\Provider\\IIROSE\\IIROSEProvider").instance.packet(
                IB.php.createInstance("Bot\\Provider\\IIROSE\\Packets\\SourcePacket",
                    e.input.getArgument("message"))
            );
            e.sender.sendMessage("已发送"+e.input.getArgument("message"));
        }
    });