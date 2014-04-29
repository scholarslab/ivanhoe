
@valid_game = {
  :game_title => Faker::Lorem.words(rand(2..8)),
  :game_description => Faker::Lorem.paragraphs(rand(1..3))
}

@valid_game_with_media = {
  :game_title => Faker::Lorem.words(rand(2..8)),
  :game_media => "",
  :game_description => Faker::Lorem.paragraphs(rand(1..3))
}

@invalid_title = {
  :game_title => "",
  :game_description => Faker::Lorem.paragraphs(rand(1..3))
}

@invalid_description = {
  :game_title => Faker::Lorem.words(rand(2..8)),
  :game_description => ""
}

class Game
  def initialize
    
  end
end
