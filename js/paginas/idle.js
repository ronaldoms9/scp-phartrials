	var UIIdleTimeout = function() {
		return {
			init: function() {
				var o;
				$("body").append(""), $.idleTimeout("#idle-timeout-dialog", ".modal-content button:last", {
					idleAfter: 180,
					timeout: 3000,
					pollingInterval: 15,
					keepAliveURL: "keepalive.php",
					serverResponseEquals: "OK",
					onTimeout: function() {
						window.location = "usu_bloqueado.php"
					},
					onIdle: function() {
						$("#idle-timeout-dialog").modal("show"), o = $("#idle-timeout-counter"), $("#idle-timeout-dialog-keepalive").on("click", function() {
							$("#idle-timeout-dialog").modal("hide")
						}), $("#idle-timeout-dialog-logout").on("click", function() {
							$("#idle-timeout-dialog").modal("hide"), $.idleTimeout.options.onTimeout.call(this)
						})
					},
					onCountdown: function(e) {
						document.getElementById("countdown").innerHTML =  e;  
						//o.html(e)
					}
				})
			}
		}
	}();
	jQuery(document).ready(function() {
		//UIIdleTimeout.init()
	});         