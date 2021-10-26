FROM alpine:3.14
RUN apk --no-cache add \
    composer \
    php7 \
    php7-ctype \
    php7-dom \
    php7-simplexml \
    php7-tokenizer \
    php7-xml \
    php7-xmlwriter
WORKDIR /app
COPY . .
RUN composer --no-cache install
ENTRYPOINT ["./docker-entrypoint.sh"]
