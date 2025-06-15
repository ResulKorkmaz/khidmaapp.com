// Açıklama: WebSocket Gateway - real-time bildirimler
import {
  WebSocketGateway,
  WebSocketServer,
  SubscribeMessage,
  OnGatewayInit,
  OnGatewayConnection,
  OnGatewayDisconnect,
} from '@nestjs/websockets';
import { Logger } from '@nestjs/common';
import { Server, Socket } from 'socket.io';

@WebSocketGateway({
  cors: {
    origin: process.env.ALLOWED_ORIGINS?.split(',') || ['http://localhost:3000'],
    credentials: true,
  },
  namespace: '/notifications',
})
export class NotificationGateway
  implements OnGatewayInit, OnGatewayConnection, OnGatewayDisconnect
{
  @WebSocketServer() server: Server;
  private logger: Logger = new Logger('NotificationGateway');
  private connectedUsers = new Map<string, string>(); // socketId -> userId

  afterInit(server: Server) {
    this.logger.log('🔌 WebSocket Gateway initialized');
  }

  handleConnection(client: Socket) {
    const userId = client.handshake.auth?.userId;
    if (userId) {
      this.connectedUsers.set(client.id, userId);
      client.join(`user:${userId}`);
      this.logger.log(`✅ User ${userId} connected with socket ${client.id}`);
    } else {
      this.logger.warn(`❌ Connection rejected: No user ID provided`);
      client.disconnect();
    }
  }

  handleDisconnect(client: Socket) {
    const userId = this.connectedUsers.get(client.id);
    if (userId) {
      this.connectedUsers.delete(client.id);
      this.logger.log(`❌ User ${userId} disconnected`);
    }
  }

  @SubscribeMessage('join-room')
  handleJoinRoom(client: Socket, payload: { room: string }) {
    const userId = this.connectedUsers.get(client.id);
    if (userId && payload.room) {
      client.join(payload.room);
      this.logger.log(`User ${userId} joined room: ${payload.room}`);
      client.emit('joined-room', { room: payload.room });
    }
  }

  @SubscribeMessage('leave-room')
  handleLeaveRoom(client: Socket, payload: { room: string }) {
    const userId = this.connectedUsers.get(client.id);
    if (userId && payload.room) {
      client.leave(payload.room);
      this.logger.log(`User ${userId} left room: ${payload.room}`);
      client.emit('left-room', { room: payload.room });
    }
  }

  // Send notification to specific user
  sendToUser(userId: string, event: string, data: any) {
    this.server.to(`user:${userId}`).emit(event, data);
    this.logger.log(`📤 Sent ${event} to user ${userId}`);
  }

  // Send notification to specific room (e.g., service request)
  sendToRoom(room: string, event: string, data: any) {
    this.server.to(room).emit(event, data);
    this.logger.log(`📤 Sent ${event} to room ${room}`);
  }

  // Send notification to all connected clients
  broadcast(event: string, data: any) {
    this.server.emit(event, data);
    this.logger.log(`📢 Broadcasted ${event} to all clients`);
  }

  // Get connection count for monitoring
  getConnectionCount(): number {
    return this.connectedUsers.size;
  }
} 