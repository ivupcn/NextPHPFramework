<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<script type="text/javascript">
	onLoad();
	onResize();
	var tl;
        function onLoad() {
            var eventSource = new Timeline.DefaultEventSource();
            
            var zones = [];
            var zones2 = [];
            
            var theme = Timeline.ClassicTheme.create();
            theme.event.bubble.width = 250;
            
            var date = "<?php echo gmdate('l M d Y',time());?>"
            var bandInfos = [
                Timeline.createHotZoneBandInfo({
                    width:          "90%", 
                    intervalUnit:   Timeline.DateTime.DAY, 
                    intervalPixels: 100,
                    zones:          zones,
                    eventSource:    eventSource,
                    date:           date,
                    timeZone:       -8
                  //  theme:          theme
                }),
                Timeline.createHotZoneBandInfo({
                    width:          "10%", 
                    intervalUnit:   Timeline.DateTime.MONTH, 
                    intervalPixels: 200,
                    zones:          zones2, 
                    eventSource:    eventSource,
                    date:           date, 
                    timeZone:       -8,
                    overview:       true
                   // theme:          theme
                })
            ];
            bandInfos[1].syncWith = 0;
            bandInfos[1].highlight = true;
            
            for (var i = 0; i < bandInfos.length; i++) {
                bandInfos[i].decorators = [];
            }
            
            tl = Timeline.create(document.getElementById("tl"), bandInfos, Timeline.HORIZONTAL);
            tl.loadXML("<?php echo $this->_context->url('task::timelinedata@oa','userid/'.$userid);?>", function(xml, url) { eventSource.loadXML(xml, url); });
        }
        
        var resizeTimerID = null;
        function onResize() {
            if (resizeTimerID == null) {
                resizeTimerID = window.setTimeout(function() {
                    resizeTimerID = null;
                    tl.layout();
                }, 500);
            }
        }
        
	</script>
	<style type="text/css">
        .t-highlight1 { background-color: #ccf; }
        .p-highlight1 { background-color: #fcc; }

        .timeline-highlight-label-start .label_t-highlight1 { color: #f00; }
        .timeline-highlight-label-end .label_t-highlight1 { color: #aaf; }

        .timeline-band-events .important { color: #f00; }
        .timeline-band-events .small-important { background: #c00; }


        /*---------------------------------*/

        .dark-theme { color: #eee; }
        .dark-theme .timeline-band-0 .timeline-ether-bg { background-color: #333 }
        .dark-theme .timeline-band-1 .timeline-ether-bg { background-color: #111 }
        .dark-theme .timeline-band-2 .timeline-ether-bg { background-color: #222 }
        .dark-theme .timeline-band-3 .timeline-ether-bg { background-color: #444 }

        .dark-theme .t-highlight1 { background-color: #003; }
        .dark-theme .p-highlight1 { background-color: #300; }

        .dark-theme .timeline-highlight-label-start .label_t-highlight1 { color: #f00; }
        .dark-theme .timeline-highlight-label-end .label_t-highlight1 { color: #115; }

        .dark-theme .timeline-band-events .important { color: #c00; }
        .dark-theme .timeline-band-events .small-important { background: #c00; }

        .dark-theme .timeline-date-label-em { color: #fff; }
        .dark-theme .timeline-ether-lines { border-color: #555; border-style: solid; }
        .dark-theme .timeline-ether-highlight { background: #555; }

        .dark-theme .timeline-event-tape,
        .dark-theme .timeline-small-event-tape { background: #f60; }
        .dark-theme .timeline-ether-weekends { background: #111; }

        .dark-theme .timeline-event-tape, .dark-theme .timeline-small-event-tape {background: #f60;}
    </style>
    <div id="tl" class="timeline-default dark-theme" style="width:100%; height:550px;" ></div>
</div>