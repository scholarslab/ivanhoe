require 'spec_helper'

describe 'Responding to multiple moves', :type => :feature, :js => true do

    include ApplicationHelper

    @valid_game = {
        :game_title => Faker::Lorem.words(rand(2..8)),
        :game_description => Faker::Lorem.paragraphs(rand(1..3))
    }

before(:each) do
    visit(URL_BASE)
     click_link 'Games'
     login
     rand(2..3).times { make_game }
end

describe "a move"

    before do
      first('.game-link').click
      make_role
      @n = rand(2...10)
      @n.times { make_a_move }
      respond_to_multiple_moves
    end

    it 'should list multiple sources in the source column' do
      source_block = first('#moves article .game-discussion-source')
      within(source_block) do
        expect(find('ul')).to have_selector('li', count: @n)
      end
    end


    # create random number n
    # create n moves
    # respond to a random number of moves between 2 and n
    # that means clicking .new_source of n different moves
    # check for that number of list items in the it block

end
