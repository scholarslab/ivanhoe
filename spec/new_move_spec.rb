require 'spec_helper'

describe 'Make a Move View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        game = make_game
        first('a .game-title').click
        make_role('admin', game.id)
    end

    describe 'for a move with no source' do

      before (:each) do
        click_button('Make a Move')
      end

      it 'has the Make a Move header' do
        expect(page).to have_content('MAKE A MOVE')
      end

      it 'has a move title label' do
        expect(page).to have_content('Move Title')
      end

      it 'has a required move title field' do
        expect(page).to have_field('post_title', :type => 'text')
        click_button 'Save'
        expect(page).to have_content('A title is required')
      end

      it 'has a move content label' do
        expect(page).to have_content('Move Content')
      end

      it 'has the Add Media button' do
        expect(page).to have_selector('#insert-media-button')
      end

      it 'has a required move content input' do
        expect(page).to have_selector('#wp-post_content-wrap')
        # not sure if this is the best selector for this
        click_button 'Save'
        expect(page).to have_content('A description is required')
      end

      it 'has the rationale label' do
        expect(page).to have_content('Rationale')
      end

      it 'has a rationale input' do
        expect(page).to have_selector('#wp-post_content-wrap')
        # use a within block to specify these?
      end

      it 'has a save button' do
        expect(page).to have_selector('input[type=submit][value=Save]')
      end

      it 'has the move meta box' do
        expect(page).to have_selector('.new-ivanhoe-meta')
      end

      it 'has a link to the game with which the move is connected' do
        within('.new-ivanhoe-meta') do
          expect(page).to have_content('making a move on the game')
          expect(page).to have_selector('a')
        end
      end

    end

    describe 'for a move that is a response' do

      before do
        make_a_move
        first('.new_source').click
        click_button 'Respond'
      end

      it 'has the move meta box' do
        expect(page).to have_selector('.new-ivanhoe-meta')
      end

      it 'has a link to the game with which the move is connected' do
        within('.new-ivanhoe-meta') do
          expect(page).to have_content('making a move on the game')
          expect(page).to have_selector('a')
        end
      end

      it 'has a link to the move to which the move responds' do
        within('.new-ivanhoe-meta') do
          # trying to figure out a way to target the two links within the meta field
          # given that they have neither id nor class
          # links = page.all('a')
          # game_link = links[0]
          # move_link = links[1]
          expect(page).to have_content('in response to the following')
          expect(page).to have_selector('a')
        end
      end

    end

end
