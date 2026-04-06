#######################################################################################################################
""" 
SNAKE GAME ENHANCEMENTS: 
SOUND EFFECTS WHEN EATING FOOD AND DYING (AND BG MUSIC)
CAN CONTROL WITH ARROW KEYS OR WASD
CAN WRAP AROUND THE SCREEN INSTEAD OF DYING WHEN HITTING THE EDGE (EASY MODE) OR CAN DIE WHEN HITTING THE EDGE (HARD MODE)
PAUSE FUNCTIONALITY (PRESS P TO PAUSE/UNPAUSE)
SNAKE GRADIENT COLOR (PINK TO PURPLE) INSTEAD OF SOLID COLOR 
"""

"""
Sound effects from Roblox and Pokemon Music Collection (links in code comments)
Text font from Grand9K Pixel (Minecraft-style font) (link in code comments)
"""
#######################################################################################################################


import pygame
import time
import random
 
pygame.init()
 
white = (255, 255, 255)
yellow = (255, 255, 204)
black = (0, 0, 0)
red = (255, 102, 102)
pastelPink = (255, 204, 229)
green = (229, 255, 204)
blue = (204, 229, 255)
purple = (204, 204, 255)

dis_width = 600
dis_height = 400
 
dis = pygame.display.set_mode((dis_width, dis_height))
pygame.display.set_caption('Snake Game')
 
clock = pygame.time.Clock()
 
snake_block = 10
snake_speed = 15
 
font_style = pygame.font.Font("resources/Grand9K Pixel.ttf", 18)
score_font = pygame.font.Font("resources/Grand9K Pixel.ttf", 15)

color = green
food_value = 1

score = 0

x1 = dis_width / 2
y1 = dis_height / 2
 
def Your_score(score):
    value = score_font.render("  Your Score: " + str(score), True, yellow)
    dis.blit(value, [0, 0])

# GRADIENT SNAKE FUNCTION
def our_snake(snake_list):
    global color, x1, y1, pastelPink, purple

    snake_block = 10
    start_color = purple
    end_color = pastelPink
    length = len(snake_list)

    for i in range(length):
        # calc how far along the snake this segment is (0 to 1)
        if length > 1:
            t = i / (length - 1)
        else:
            t = 0  # avoid division by 0

        # calculate the rgb values seperately to create the gradient effect
        r = int(start_color[0] * (1 - t) + end_color[0] * t)
        g = int(start_color[1] * (1 - t) + end_color[1] * t)
        b = int(start_color[2] * (1 - t) + end_color[2] * t)

        # create  color tuple for the segment
        segment_color = (r, g, b)

        # get the position of the current segment
        x = int(snake_list[i][0])
        y = int(snake_list[i][1])

        pygame.draw.rect(dis, segment_color, [x, y, snake_block, snake_block])


 
def message(msg, color):
    mesg = font_style.render(msg, True, color)
    dis.blit(mesg, [dis_width / 6, dis_height / 3])

# GAME MODE SELECTION FUNCTION
def select_mode():
    selecting = True
    mode = "easy"  # default

    while selecting:
        dis.fill(black)
        msg1 = font_style.render("Select Mode:", True, yellow)
        msg2 = font_style.render("E - Easy (Wrap Around)", True, green)
        msg3 = font_style.render("H - Hard (Wall Death)", True, red)
        dis.blit(msg1, [dis_width / 4, dis_height / 4])
        dis.blit(msg2, [dis_width / 4, dis_height / 4 + 40])
        dis.blit(msg3, [dis_width / 4, dis_height / 4 + 80])
        pygame.display.update()

        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                pygame.quit()
                quit()
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_e:
                    mode = "easy"
                    selecting = False
                elif event.key == pygame.K_h:
                    mode = "hard"
                    selecting = False
    return mode
 
 
def gameLoop():
    global snake_speed, food_value, color, score, x1, y1

    game_mode = select_mode()

    paused = False

    score = 0

    color = green
    # music from https://downloads.khinsider.com/game-soundtracks/album/pokemon-diamond-and-pearl-super-music-collection/1-24.%2520Pok%25C3%25A9%2520Mart.mp3
    bgMusic = pygame.mixer.Sound('sfx/PokeMart.mp3')
    bgMusic.play(loops=-1)

    game_over = False
    game_close = False
 
    x1_change = 0
    y1_change = 0

    snake_List = []
    Length_of_snake = 1
 
    foodx = round(random.randrange(0, dis_width - snake_block) / 10.0) * 10.0
    foody = round(random.randrange(0, dis_height - snake_block) / 10.0) * 10.0
 
    while not game_over:
        i = 0

        while game_close == True:            
            i += 1
            dis.fill(black)
            if i == 1:
                bgMusic.stop()
                # sfx from Roblox (https://www.myinstants.com/en/instant/roblox-death-sound-effect-2459/)
                sound_effect = pygame.mixer.Sound('sfx/Oof.mp3')
                sound_effect.play(loops=0)
            message("You Lost! Press C-Play Again or Q-Quit", red)
            Your_score(Length_of_snake - 1)

            pygame.display.update()
 
            for event in pygame.event.get():
                if event.type == pygame.KEYDOWN:
                    if event.key == pygame.K_q:
                        game_over = True
                        game_close = False
                    if event.key == pygame.K_c:
                        gameLoop()
 

        # PAUSE FUNCTIONALITY (PRESS P TO PAUSE/UNPAUSE)
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                game_over = True
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_p:
                    paused = not paused  # toggle pause

                if not paused:
                    if event.key == pygame.K_LEFT or event.key == pygame.K_a:
                        x1_change = -snake_block
                        y1_change = 0
                    elif event.key == pygame.K_RIGHT or event.key == pygame.K_d:
                        x1_change = snake_block
                        y1_change = 0
                    elif event.key == pygame.K_UP or event.key == pygame.K_w:
                        y1_change = -snake_block
                        x1_change = 0
                    elif event.key == pygame.K_DOWN or event.key == pygame.K_s:
                        y1_change = snake_block
                        x1_change = 0
        # PAUSE SCREEN
        if paused:
            dis.fill(black)
            pause_text = font_style.render("Game Paused. Press P to Resume.", True, yellow)
            dis.blit(pause_text, [dis_width / 6, dis_height / 3])
            Your_score(score)
            pygame.display.update()
            continue  # skip the rest of the game loop to freeze game state


        x1 += x1_change
        y1 += y1_change
        
        if game_mode == "easy":
            # wrap around
            if x1 >= dis_width:
                x1 = 0
            elif x1 < 0:
                x1 = dis_width - snake_block

            if y1 >= dis_height:
                y1 = 0
            elif y1 < 0:
                y1 = dis_height - snake_block

        elif game_mode == "hard":
            # wall death
            if x1 >= dis_width or x1 < 0 or y1 >= dis_height or y1 < 0:
                game_close = True


        dis.fill(black)
        pygame.draw.rect(dis, color, [foodx, foody, snake_block, snake_block])
        snake_Head = []
        snake_Head.append(x1)
        snake_Head.append(y1)
        snake_List.append(snake_Head)
        if len(snake_List) > Length_of_snake:
            del snake_List[0]
 
        for x in snake_List[:-1]:
            if x == snake_Head:
                game_close = True
 
        our_snake(snake_List)
        score = (Length_of_snake - 1)
        Your_score(score)
 
        pygame.display.update()
 
        if x1 == foodx and y1 == foody:
            sound_effect = pygame.mixer.Sound('sfx/NumNumNum.mp3')
            sound_effect.play(loops=0)
            foodx = round(random.randrange(0, dis_width - snake_block) / 10.0) * 10.0
            foody = round(random.randrange(0, dis_height - snake_block) / 10.0) * 10.0
            Length_of_snake += 1

        clock.tick(snake_speed)
 
    pygame.quit()
    quit()
 
 
gameLoop()
