FROM node:latest

RUN mkdir -p /home/apps/vending_machine

WORKDIR /home/apps/vending_machine

COPY vending_machine /home/apps/vending_machine

RUN npm config set strict-ssl false
RUN npm install --force --loglevel verbose
RUN npm run build

EXPOSE 3000

CMD ["npx", "serve", "-s", "build"]
#CMD ["npm", "start"]
