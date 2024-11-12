FROM node:latest

RUN mkdir -p /home/apps/vending_machine

WORKDIR /home/apps/vending_machine

COPY vending_machine /home/apps/vending_machine


EXPOSE 3000

#CMD ["serve", "-s build"]
CMD ["npm", "start"]
