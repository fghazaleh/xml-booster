#!/bin/bash

help(){
	echo "Usage:"
	printf "\t build\t: Build container.\n"
	printf "\t down\t: Stop all services (e.g. PHP).\n"
	printf "\t enter\t: Enter container.\n"
	printf "\t help\t: Show this help.\n"
	printf "\t up\t: Launch the container.\n"
	exit 0
}

if [[ -z $1 ]];then
	help
	exit 0
fi

case $1 in
	build)
		docker-compose build --no-cache
		;;
	up)
		docker-compose up -d
		docker-compose exec php /bin/sh -c "cd /var/www/html && composer install --no-dev && exit && /bin/sh"
		;;
	down)
		docker-compose down
		;;
	-e | enter)
		docker-compose exec php /bin/sh -c "cd /var/www/html && /bin/sh"
		;;
	* | -h | help)
		help
		;;
esac