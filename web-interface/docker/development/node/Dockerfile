FROM node:24-alpine

RUN apk add --no-cache curl bash

WORKDIR /app

#RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

#USER app
USER node

#HEALTHCHECK --interval=5s --timeout=5s --start-period=1s CMD curl --fail http://127.0.0.1:3000/health || exit 1
