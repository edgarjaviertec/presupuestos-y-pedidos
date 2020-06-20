<style>
	.spinner-wrapper {
		display: flex;
		justify-content: center;
		align-items: center;
		height: calc(100vh - 80px);
		width: 100%;
	}

	.spinner {
		text-align: center;
	}

	.spinner > div {
		width: 24px;
		height: 24px;
		background-color: #78909C;
		border-radius: 100%;
		display: inline-block;
		-webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
		animation: sk-bouncedelay 1.4s infinite ease-in-out both;
	}

	.spinner .bounce1 {
		-webkit-animation-delay: -0.32s;
		animation-delay: -0.32s;
	}

	.spinner .bounce2 {
		-webkit-animation-delay: -0.16s;
		animation-delay: -0.16s;
	}

	@-webkit-keyframes sk-bouncedelay {
		0%, 80%, 100% {
			-webkit-transform: scale(0)
		}
		40% {
			-webkit-transform: scale(1.0)
		}
	}

	@keyframes sk-bouncedelay {
		0%, 80%, 100% {
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		40% {
			-webkit-transform: scale(1.0);
			transform: scale(1.0);
		}
	}
</style>
<div id="spinner" class="spinner-wrapper">
	<div class="spinner">
		<div class="bounce1"></div>
		<div class="bounce2"></div>
		<div class="bounce3"></div>
	</div>
</div>
