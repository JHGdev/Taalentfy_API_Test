create-docker:
ifeq ($(wildcard .docker/.env), )
	cp .docker/.env.dev .docker/.env
endif
	$(MAKE) -C .docker all
